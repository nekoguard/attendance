
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">課マスタ管理</h2>
    </x-slot>
    <div class="py-10 px-4 max-w-4xl mx-auto">
        <div class="bg-white rounded shadow p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <a href="{{ route('admin.sections.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow">＋ 新規追加</a>
                @if(session('success'))
                    <div class="px-4 py-2 bg-green-100 text-green-700 border border-green-300 rounded">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="px-4 py-2 bg-red-100 text-red-700 border border-red-300 rounded">{{ session('error') }}</div>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border rounded">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border px-4 py-2 w-1/6">コード</th>
                            <th class="border px-4 py-2 w-1/3">課名</th>
                            <th class="border px-4 py-2 w-1/4">部署</th>
                            <th class="border px-4 py-2">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sections as $sect)
                        <tr class="even:bg-gray-50">
                            <td class="border px-4 py-2">{{ $sect->code }}</td>
                            <td class="border px-4 py-2">{{ $sect->name }}</td>
                            <td class="border px-4 py-2">{{ optional($sect->department)->name }}</td>
                            <td class="border px-4 py-2 text-center">
                                <a href="{{ route('admin.sections.edit', $sect->id) }}" class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded mr-2">編集</a>
                                <form action="{{ route('admin.sections.delete', $sect->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded" onclick="return confirm('本当に削除しますか？')">削除</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
