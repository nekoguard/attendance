<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\WorkType;

class MasterStatusWorkTypeController extends Controller
{
    public function index()
    {
        $statuses = Status::all();
        $workTypes = WorkType::all();

        return view('admin.master.status_work_type', compact('statuses', 'workTypes'));
    }

    // 在籍状態保存
    public function storeStatus(Request $request)
    {
        $request->validate(
            ['name' => 'required|string|max:255'],
            [
                'name.required' => '在籍状態は必須です。',
                'name.string' => '在籍状態は文字列である必要があります。',
                'name.max' => '在籍状態は255文字以内である必要があります。'
            ]
        );
        Status::create(['name' => $request->name]);

        return redirect()->route('admin.master.status_work_type.index')->with('success', '在籍状態を追加しました');
    }

    // 在籍状態更新
    public function updateStatus(Request $request, $id)
    {
        $request->validate(
            ['name' => 'required|string|max:255'],
            [
                'name.required' => '在籍状態は必須です。',
                'name.string' => '在籍状態は文字列である必要があります。',
                'name.max' => '在籍状態は255文字以内である必要があります。'
            ]
        );
        $status = Status::findOrFail($id);
        $status->update(['name' => $request->name]);

        return redirect()->route('admin.master.status_work_type.index')->with('success', '在籍状態を更新しました');
    }

    // 在籍状態削除
    public function destroyStatus($id)
    {
        Status::destroy($id);

        return redirect()->route('admin.master.status_work_type.index')->with('success', '在籍状態を削除しました');
    }

    // 勤務区分保存
    public function storeWorkType(Request $request)
    {
        $request->validate(
            ['name' => 'required|string|max:255'],
            [
                'name.required' => '勤務区分は必須です。',
                'name.string' => '勤務区分は文字列である必要があります。',
                'name.max' => '勤務区分は255文字以内である必要があります。'
            ]
        );
        WorkType::create(['name' => $request->name]);

        return redirect()->route('admin.master.status_work_type.index')->with('success', '勤務区分を追加しました');
    }

    // 勤務区分更新
    public function updateWorkType(Request $request, $id)
    {
        $request->validate(
            ['name' => 'required|string|max:255'],
            [
                'name.required' => '勤務区分は必須です。',
                'name.string' => '勤務区分は文字列である必要があります。',
                'name.max' => '勤務区分は255文字以内である必要があります。'
            ]
        );
        $workType = WorkType::findOrFail($id);
        $workType->update(['name' => $request->name]);

        return redirect()->route('admin.master.status_work_type.index')->with('success', '勤務区分を更新しました');
    }

    // 勤務区分削除
    public function destroyWorkType($id)
    {
        WorkType::destroy($id);

        return redirect()->route('admin.master.status_work_type.index')->with('success', '勤務区分を削除しました');
    }
}
