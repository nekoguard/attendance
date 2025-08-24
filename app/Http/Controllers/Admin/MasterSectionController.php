<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Department;

class MasterSectionController extends Controller
{
    // 課一覧
    public function index()
    {
        $sections = Section::with('department')->orderBy('department_id')->orderBy('code')->get();
        
        return view('admin.sections.index', compact('sections'));
    }

    // 課新規作成
    public function createSection()
    {
        $departments = Department::orderBy('code')->get();

        return view('admin.sections.create', compact('departments'));
    }

    // 課保存
    public function storeSection(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:sections,code',
            'department_id' => 'required|exists:departments,id',
        ],
        [
            'name.required' => '課名は必須です。',
            'code.required' => '課コードは必須です。',
            'code.unique' => 'この課コードは既に登録されています。',
            'department_id.required' => '部署を選択してください。',
            'department_id.exists' => '選択した部署が存在しません。',
        ]);
        
        Section::create($request->only('name', 'code', 'department_id'));

        return redirect()->route('admin.sections')->with('success', '課を追加しました');
    }

    // 課編集
    public function editSection($id)
    {
        $section = Section::findOrFail($id);
        $departments = Department::orderBy('code')->get();

        return view('admin.sections.edit', compact('section', 'departments'));
    }

    // 課更新
    public function updateSection(Request $request, $id)
    {
        $section = Section::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'department_id' => 'required|exists:departments,id',
        ],
        [
            'name.required' => '課名は必須です。',
            'code.required' => '課コードは必須です。',
            'department_id.required' => '部署を選択してください。',
            'department_id.exists' => '選択した部署が存在しません。',
        ]);
        $section->update($request->only('name', 'code', 'department_id'));

        return redirect()->route('admin.sections')->with('success', '課を更新しました');
    }

    // 課削除
    public function deleteSection($id)
    {
        $section = Section::findOrFail($id);

        // 紐づくユーザーが存在する場合は削除しない
        if ($section->userInfos()->exists()) {
            return redirect()->route('admin.sections')
                ->with('error', 'この課に紐づくユーザーが存在するため削除できません');
        }

        $section->delete();

        return redirect()->route('admin.sections')->with('success', '課を削除しました');
    }
}
