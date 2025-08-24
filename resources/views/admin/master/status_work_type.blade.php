
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">在籍状態・勤務区分マスタ管理</h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4">
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
        @if(session('success'))
            <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 border border-green-300 rounded">{{ session('success') }}</div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- 在籍状態 -->
            <div class="bg-white rounded shadow p-6 flex flex-col">
                <h3 class="font-semibold mb-4 text-lg border-b pb-2">在籍状態</h3>
                <form action="{{ route('admin.master.status_work_type.status.store') }}" method="POST" class="mb-4 flex gap-2">
                    @csrf
                    <input type="text" name="name" class="border rounded px-3 py-2 flex-1" placeholder="新規在籍状態">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">追加</button>
                </form>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border rounded">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border px-3 py-2 w-2/3">名称</th>
                                <th class="border px-3 py-2 w-1/3">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($statuses as $status)
                        <tr class="even:bg-gray-50">
                            <form action="{{ route('admin.master.status_work_type.status.update', $status->id) }}" method="POST" class="flex items-center">
                                @csrf @method('PUT')
                                <td class="border px-3 py-2"><input type="text" name="name" value="{{ $status->name }}" class="w-full border rounded px-2 py-1"></td>
                                <td class="border px-3 py-2 flex gap-2 justify-center">
                                    <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">更新</button>
                            </form>
                            <form action="{{ route('admin.master.status_work_type.status.destroy', $status->id) }}" method="POST" onsubmit="return confirm('削除しますか？')">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">削除</button>
                            </form>
                                </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 勤務区分 -->
            <div class="bg-white rounded shadow p-6 flex flex-col">
                <h3 class="font-semibold mb-4 text-lg border-b pb-2">勤務区分</h3>
                <form action="{{ route('admin.master.status_work_type.work_type.store') }}" method="POST" class="mb-4 flex gap-2">
                    @csrf
                    <input type="text" name="name" class="border rounded px-3 py-2 flex-1" placeholder="新規勤務区分">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">追加</button>
                </form>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border rounded">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border px-3 py-2 w-2/3">名称</th>
                                <th class="border px-3 py-2 w-1/3">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($workTypes as $workType)
                        <tr class="even:bg-gray-50">
                            <form action="{{ route('admin.master.status_work_type.work_type.update', $workType->id) }}" method="POST" class="flex items-center">
                                @csrf @method('PUT')
                                <td class="border px-3 py-2"><input type="text" name="name" value="{{ $workType->name }}" class="w-full border rounded px-2 py-1"></td>
                                <td class="border px-3 py-2 flex gap-2 justify-center">
                                    <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">更新</button>
                            </form>
                            <form action="{{ route('admin.master.status_work_type.work_type.destroy', $workType->id) }}" method="POST" onsubmit="return confirm('削除しますか？')">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">削除</button>
                            </form>
                                </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
