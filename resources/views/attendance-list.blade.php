<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-4">
            {{ __('在籍一覧') }}
            <span class="text-base font-normal text-gray-500">{{ now()->format('Y-m-d') }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full mt-4 text-sm table-auto border-collapse">
                        <thead class="bg-gray-100 border-b border-gray-300 sticky top-0 z-1">
                            <tr>
                                <th class="text-left px-4 py-2 text-center border-table w-[16%]">部署</th>
                                <th class="text-left px-4 py-2 text-center border-table w-[16%]">課</th>
                                <th class="text-left px-4 py-2 text-center border-table w-[12%]">社員番号</th>
                                <th class="text-left px-4 py-2 text-center border-table w-[16%]">氏名</th>
                                <th class="text-left px-4 py-2 text-center border-table">勤務区分</th>
                                <th class="text-left px-4 py-2 text-center border-table">出退勤</th>
                                <th class="text-left px-4 py-2 text-center border-table">在籍状態</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $deptCount = [];
                                $sectCount = [];
                            @endphp
                            @forelse($users as $user)
                                <tr class="border-b border-gray-200">
                                    @php
                                        $dept = $user->department->name ?? '';
                                        $sect = $user->section->name ?? '';
                                        $deptSectKey = $dept.'|'.$sect;
                                        $deptCount[$dept] = ($deptCount[$dept] ?? 0) + 1;
                                        $sectCount[$deptSectKey] = ($sectCount[$deptSectKey] ?? 0) + 1;
                                    @endphp
                                    @if($deptCount[$dept] === 1)
                                        <td class="px-4 py-2 text-center border-table" rowspan="{{ $deptRowspans[$dept] }}">{{ $dept }}</td>
                                    @endif
                                    @if($sectCount[$deptSectKey] === 1)
                                        <td class="px-4 py-2 text-center border-table" rowspan="{{ $sectRowspans[$deptSectKey] }}">{{ $sect }}</td>
                                    @endif
                                    <td class="px-4 py-2 text-center border-table">{{ $user->user->name ?? '' }}</td>
                                    <td class="px-4 py-2 text-center border-table">{{ $user->last_name }} {{ $user->first_name }}</td>
                                    <td class="px-4 py-2 text-center border-table">{{ optional($user->workType)->name }}</td>
                                    <td class="px-4 py-2 text-center border-table">
                                        @php
                                            $att = $user->attendances->first();
                                        @endphp
                                        @if($att)
                                            @if($att->clock_out_at)
                                                <span class="text-gray-700">{{ \Carbon\Carbon::parse($att->clock_out_at)->format('H:i') }}</span>
                                            @elseif($att->clock_in_at)
                                                <span class="text-blue-700">{{ \Carbon\Carbon::parse($att->clock_in_at)->format('H:i') }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border-table">
                                        <div class="flex items-center justify-between w-full">
                                            <span class="text-left">{{ optional(optional($user->attendances->first())->status)->name }}</span>
                                            <a href="{{ route('user-infos.edit', $user->id) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white text-xs px-2 py-1 rounded ml-2">編集</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-gray-500">在籍情報が存在しません。</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
