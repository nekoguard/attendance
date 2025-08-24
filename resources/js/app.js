import './bootstrap';
import Alpine from 'alpinejs';
import { setupCalendar } from './calendarCommon.js';
import { setupServerTime, handleAttendance } from './attendanceCommon.js';

// カレンダー初期化をwindowにエクスポート
window.setupCalendar = setupCalendar;

// Alpine.js初期化
window.Alpine = Alpine;
Alpine.start();

// DOMContentLoaded時の初期化処理
document.addEventListener('DOMContentLoaded', () => {
    // サーバー時刻の自動表示
    setupServerTime('time');
    // 勤怠打刻API呼び出し関数（handleAttendance）はwindowにエクスポートしてもOK
    window.handleAttendance = handleAttendance;

    // 出勤
    const clockinBtn = document.getElementById('clockin-btn');
    if (clockinBtn) {
        clockinBtn.addEventListener('click', () => handleAttendance('clock_in_at', 'clockIn', '出勤'));
    }
    // 退勤
    const clockoutBtn = document.getElementById('clockout-btn');
    if (clockoutBtn) {
        clockoutBtn.addEventListener('click', () => handleAttendance('clock_out_at', 'clockOut', '退勤'));
    }
    // 外出
    const breakstartBtn = document.getElementById('breakstart-btn');
    if (breakstartBtn) {
        breakstartBtn.addEventListener('click', () => handleAttendance('break_start_at', 'breakStart', '外出'));
    }
    // 戻り
    const breakendBtn = document.getElementById('breakend-btn');
    if (breakendBtn) {
        breakendBtn.addEventListener('click', () => handleAttendance('break_end_at', 'breakEnd', '戻り'));
    }
});
