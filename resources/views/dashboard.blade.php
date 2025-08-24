<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('トップページ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 flex flex-col items-center">
                    <div id="time" class="text-5xl font-mono font-bold mb-8 text-center"></div>
                    <div class="w-full flex flex-wrap justify-center gap-6 mb-4">
                        <div class="flex flex-col items-center">
                            <button id="clockin-btn" type="button" class="btn-parts bg-blue-600 hover:bg-blue-700 ">
                                <span class="material-icons text-2xl mb-1">login</span>
                                出勤
                            </button>
                            <div id="clockIn" class="mt-1 text-gray-500">
                                @if(!empty($attendance?->clock_in_at))
                                    出勤 {{ \Carbon\Carbon::parse($attendance->clock_in_at)->format('H:i') }}
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col items-center">
                            <button id="clockout-btn" type="button" class="btn-parts bg-green-600 hover:bg-green-700 ">
                                <span class="material-icons text-2xl mb-1">logout</span>
                                退勤
                            </button>
                            <div id="clockOut" class="mt-1 text-gray-500">
                                @if(!empty($attendance?->clock_out_at))
                                    退勤 {{ \Carbon\Carbon::parse($attendance->clock_out_at)->format('H:i') }}
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col items-center">
                            <button id="breakstart-btn" type="button" class="btn-parts bg-yellow-600 hover:bg-yellow-700 ">
                                <span class="material-icons text-2xl mb-1">directions_walk</span>
                                外出
                            </button>
                            <div id="breakStart" class="mt-1 text-gray-500">
                                @if(!empty($attendance?->break_start_at))
                                    外出 {{ \Carbon\Carbon::parse($attendance->break_start_at)->format('H:i') }}
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col items-center">
                            <button id="breakend-btn" type="button" class="btn-parts bg-purple-600 hover:bg-purple-700 ">
                                <span class="material-icons text-2xl mb-1">undo</span>
                                戻り
                            </button>
                            <div id="breakEnd" class="mt-1 text-gray-500">
                                @if(!empty($attendance?->break_end_at))
                                    戻り {{ \Carbon\Carbon::parse($attendance->break_end_at)->format('H:i') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Google Material Icons CDN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</x-app-layout>
