<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInfo;
use App\Models\Status;

class UserInfoController extends Controller
{
    public function edit($id)
    {
        $userInfo = UserInfo::findOrFail($id);
        $statuses = Status::orderBy('id')->get();
        
        return view('user-info-edit', compact('userInfo', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $userInfo = UserInfo::findOrFail($id);
        $userInfo->status_id = $request->input('status_id');
        $userInfo->save();

        return redirect()->route('attendance-list')->with('success', '在籍情報を更新しました');
    }
}
