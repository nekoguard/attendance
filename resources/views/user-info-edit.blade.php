<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('在籍情報の編集') }}
        </h2>
    </x-slot>
<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">在籍情報の編集</h2>
    <form method="POST" action="{{ route('user-infos.update', $userInfo->id) }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">在籍状態</label>
            <select name="status_id" class="border rounded px-3 py-2 w-full">
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" @if($userInfo->status_id == $status->id) selected @endif>{{ $status->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">保存</button>
        <a href="{{ route('attendance-list') }}" class="ml-4 text-gray-600 underline">戻る</a>
    </form>
</div>
</x-app-layout>
