<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">課編集</h2>
    </x-slot>
    <div class="py-8 max-w-md mx-auto">
        {{-- バリデーションエラー表示 --}}
        @if ($errors->any())
            <div class="mb-4 px-4 py-2 bg-red-100 text-red-700 border border-red-300 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.sections.update', $section->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block mb-1">課コード</label>
                <input type="text" name="code" class="border rounded px-3 py-2 w-full" value="{{ $section->code }}" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">課名</label>
                <input type="text" name="name" class="border rounded px-3 py-2 w-full" value="{{ $section->name }}" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">部署</label>
                <select name="department_id" class="border rounded px-3 py-2 w-full" required>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @if($section->department_id == $dept->id) selected @endif>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">更新</button>
            <a href="{{ route('admin.sections') }}" class="ml-4 text-gray-600 underline">戻る</a>
        </form>
    </div>
</x-app-layout>
