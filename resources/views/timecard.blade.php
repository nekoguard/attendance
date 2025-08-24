<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('タイムカード（月次）') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center mb-2">
                        <button id="prevMonth" class="px-2 py-1 border rounded mr-2">&lt;</button>
                        <span id="currentMonth" class="font-bold text-lg">{{ $month }}</span>
                        <button id="nextMonth" class="px-2 py-1 border rounded ml-2">&gt;</button>
                    </div>
                    <div id="calendar-area"></div>
                    <div class="mt-8 p-4 bg-gray-50 rounded shadow-sm w-full max-w-2xl mx-auto">
                        <h3 class="font-bold mb-2 text-gray-700">集計</h3>
                        <div class="flex flex-wrap gap-4 justify-between">
                            <div class="flex flex-col items-center flex-1 min-w-[120px]">
                                <span class="text-xs text-gray-500 mb-1">規定の出勤日数</span>
                                <span id="required-work-days" class="font-bold text-lg">-</span>
                            </div>
                            <div class="flex flex-col items-center flex-1 min-w-[120px]">
                                <span class="text-xs text-gray-500 mb-1">勤務日数</span>
                                <span id="actual-work-days" class="font-bold text-lg">-</span>
                            </div>
                            <div class="flex flex-col items-center flex-1 min-w-[120px]">
                                <span class="text-xs text-gray-500 mb-1">残業</span>
                                <span id="overtime-minutes" class="font-bold text-lg">-</span>
                            </div>
                            <div class="flex flex-col items-center flex-1 min-w-[120px]">
                                <span class="text-xs text-gray-500 mb-1">有休残</span>
                                <span id="paid-leave-remaining" class="font-bold text-lg">-</span>
                            </div>
                        </div>
                    </div>
                    @vite(['resources/js/app.js'])
                    <script>
                        // カレンダー初期化処理
                        document.addEventListener('DOMContentLoaded', function() {
                            console.log(window.setupCalendar);
                            if (window.setupCalendar) {
                                window.setupCalendar(
                                    '{{ $month }}',
                                    'calendar-area',
                                    'prevMonth',
                                    'nextMonth',
                                    'currentMonth'
                                );
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
