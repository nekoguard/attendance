<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class MasterDepartmentController extends Controller
{
    // 部署一覧
    public function index()
    {
        $departments = Department::orderBy('code')->get();
        
        return view('admin.departments.index', compact('departments'));
    }

    // 部署新規作成
    public function createDepartment()
    {
        return view('admin.departments.create');
    }

    // 部署保存
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:departments,name',
            'code' => 'required|unique:departments,code',
        ],
        [
            'name.required' => '部署名は必須です。',
            'name.unique' => 'この部署名は既に登録されています。',
            'code.required' => '部署コードは必須です。',
            'code.unique' => 'この部署コードは既に登録されています。',
        ]);
        Department::create($request->only('name', 'code'));

        return redirect()->route('admin.departments')->with('success', '部署を追加しました');
    }

    // 部署編集
    public function editDepartment($id)
    {
        $department = Department::findOrFail($id);

        return view('admin.departments.edit', compact('department'));
    }

    // 部署更新
    public function updateDepartment(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        $request->validate([
            'name' => 'required|unique:departments,name,' . $id,
            'code' => 'required|unique:departments,code,' . $id,
        ],
        [
            'name.required' => '部署名は必須です。',
            'name.unique' => 'この部署名は既に登録されています。',
            'code.required' => '部署コードは必須です。',
            'code.unique' => 'この部署コードは既に登録されています。',
        ]);
        $department->update($request->only('name', 'code'));

        return redirect()->route('admin.departments')->with('success', '部署を更新しました');
    }

    // 部署削除
    public function deleteDepartment($id)
    {
        $department = Department::findOrFail($id);

        // 紐づくユーザーが存在する場合は削除しない
        if ($department->userInfos()->exists()) {
            return redirect()->route('admin.departments')
                ->with('error', 'この部署に紐づくユーザーが存在するため削除できません');
        }

        $department->delete();

        return redirect()->route('admin.departments')->with('success', '部署を削除しました');
    }
}
