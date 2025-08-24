<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Status;
use App\Models\UserInfo;
use App\Models\Setting;

class AttendanceController extends Controller
{
    public function index()
    {
        // 部署コード順でユーザー情報を取得
        $today = now()->toDateString();
        $users = UserInfo::with([
            'department',
            'section',
            'user',
            'workType',
            'attendances' => function($query) use ($today) {
                $query->where('date', $today)->with('status');
            }
        ])
        ->whereHas('user', function($query) {
            $query->where('role', '!=', 'admin'); // 管理者は除外
        })
        ->join('departments', 'user_infos.department_id', '=', 'departments.id')
        ->join('sections', 'user_infos.section_id', '=', 'sections.id')
        ->join('users', 'user_infos.user_id', '=', 'users.id')
        ->orderBy('departments.code')
        ->orderBy('sections.code')
        ->orderBy('users.name')
        ->select('user_infos.*')
        ->get();

        // rowspan計算
        $deptRowspans = [];
        $sectRowspans = [];
        foreach ($users as $u) {
            $dept = $u->department->name ?? '';
            $sect = $u->section->name ?? '';
            if (!isset($deptRowspans[$dept])) $deptRowspans[$dept] = 0;
            $deptRowspans[$dept]++;
            $deptSectKey = $dept.'|'.$sect;
            if (!isset($sectRowspans[$deptSectKey])) $sectRowspans[$deptSectKey] = 0;
            $sectRowspans[$deptSectKey]++;
        }

        // ステータス一覧を取得
        $statuses = Status::orderBy('id')->get();

        return view('attendance-list', [
            'users' => $users,
            'statuses' => $statuses,
            'deptRowspans' => $deptRowspans,
            'sectRowspans' => $sectRowspans,
        ]);
    }

    public function timecard(Request $request)
    {
        // ログインユーザー取得
        $user = Auth::user();
        // 対象月の取得
        $month = $request->input('month', now()->format('Y-m'));
        $start = Carbon::parse($month.'-01')->startOfMonth();
        $end = (clone $start)->endOfMonth();

        // 出勤情報を取得
        $attendances = Attendance::with('status')
            ->where('user_id', $user->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date')
            ->get();

        // 集計値の計算
        // 規定の出勤日数（1.月ごと設定→2.全社共通→3.平日自動計算）
        $requiredWorkDays = null;
        $monthKey = 'required_work_days_' . str_replace('-', '_', $month); // 例: required_work_days_2025_08
        $requiredWorkDays = Setting::where('key', $monthKey)->value('value');

        // 月ごとの設定がない場合は全社共通設定を使用
        if ($requiredWorkDays === null) {
            $requiredWorkDays = Setting::where('key', 'required_work_days')->value('value');
        }

        // 全社共通設定もない場合は平日自動計算
        if ($requiredWorkDays === null) {
            // 平日自動計算
            $requiredWorkDays = 0;
            for ($day = $start->copy(); $day <= $end; $day->addDay()) {
                if ($day->isWeekday()) $requiredWorkDays++;
            }
        }

        // 勤務日数を取得（出勤 or 退勤がある日数）
        $actualWorkDays = $attendances->filter(function($attendance) {
            return $attendance->clock_in_at || $attendance->clock_out_at;
        })->count();

        // 残業（分単位合計）
        $overtimeMinutes = $attendances->sum('overtime_minutes');
        // 有休残
        $paidLeaveRemaining = optional($user->userInfo)->paid_leave_remaining;

        // 通常アクセス時はHTML、ajax/json時のみJSON返却
        if ($request->ajax() || $request->wantsJson() || $request->input('format') === 'json') {
            $data = $attendances->map(function($attendance) {
                return [
                    'date' => $attendance->date,
                    'clock_in_at' => $attendance->clock_in_at,
                    'clock_out_at' => $attendance->clock_out_at,
                    'working_time' => $attendance->working_time,
                    'status_name' => optional($attendance->status)->name,
                ];
            });

            return response()->json([
                'attendances' => $data,
                'month' => $month,
                'summary' => [
                    'required_work_days' => $requiredWorkDays,
                    'actual_work_days' => $actualWorkDays,
                    'overtime_minutes' => $overtimeMinutes,
                    'paid_leave_remaining' => $paidLeaveRemaining,
                ],
            ]);
        }

        return view('timecard', [ 'month' => $month ]);
    }

    public function updateStatus(Request $request, $attendanceId)
    {
        $attendance = Attendance::findOrFail($attendanceId);
        // 権限チェック
        if ($attendance->user_id !== Auth::id()) {
            abort(403, '権限がありません');
        }

        $request->validate([
            'status_id' => 'required|exists:statuses,id',
        ]);

        // ステータスを更新
        $attendance->status_id = $request->input('status_id');
        $attendance->save();

        return redirect()->back()->with('success', '在籍状態を更新しました');
    }

    public function stamp(Request $request)
    {
        // 打刻種別の配列
        $allowed = ['clock_in_at', 'clock_out_at', 'break_start_at', 'break_end_at'];
        // 打刻種別を取得
        $field = $request->input('field');

        // 打刻種別が不正な場合
        if (!in_array($field, $allowed, true)) {
            return response()->json([
                'success' => false,
                'message' => '不正なアクセスです',
            ], 400);
        }

        return $this->updateAttendanceField($field);
    }

    private function updateAttendanceField($field)
    {
        // ユーザー情報を取得
        $user = Auth::user();
        // 今日の日付を取得
        $today = now()->toDateString();

        // 本日の出勤情報を取得
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // statusesテーブルからfieldで直接取得
        $status = Status::where('field', $field)->first();
        $statusId = $status ? $status->id : null;

        // 出勤打刻した場合、出勤情報がなければ新規作成
        if ($field === 'clock_in_at') {
            if ($attendance) {
                $attendance->clock_in_at = now();
                $attendance->status_id = $statusId;
                $attendance->save();
            } else {
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'clock_in_at' => now(),
                    'status_id' => $statusId,
                ]);
            }

        // それ以外の場合は時間とステータス更新
        } else if ($attendance) {
            $attendance->$field = now();
            $attendance->status_id = $statusId;
            $attendance->save();

        // 出勤していない場合はエラー
        } else {
            return response()->json([
                'success' => false,
                'message' => '出勤記録がありません',
            ], 404);
        }

        // 出勤・退勤が両方ある場合は勤務時間計算
        if ($attendance && $attendance->clock_in_at && $attendance->clock_out_at) {
            $start = Carbon::parse($attendance->clock_in_at);
            $end = Carbon::parse($attendance->clock_out_at);

            // 勤務時間を計算
            $minutes = $start->diffInMinutes($end);
            $attendance->working_time = $minutes;
            $attendance->save();
        }

        return response()->json([
            'success' => true,
            $field => $attendance->$field,
            'status' => $status,
            'attendance' => $attendance,
        ]);
    }
}
