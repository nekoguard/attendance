
/**
 * 月次カレンダーをセットアップ・描画する共通関数
 * @param {string} initialMonth - 初期表示する月（YYYY-MM形式）
 * @param {string} areaId - カレンダーを描画する要素のID
 * @param {string} prevBtnId - 前月ボタンの要素ID
 * @param {string} nextBtnId - 次月ボタンの要素ID
 * @param {string} currentMonthId - 現在の月を表示する要素ID
 */
export function setupCalendar(initialMonth, areaId, prevBtnId, nextBtnId, currentMonthId) {
    // 現在表示中の月
    let currentMonthStr = initialMonth;
    // カレンダー表示エリア
    const calendarArea = document.getElementById(areaId);
    // 前月・次月ボタン、月表示エリア
    const prevMonthBtn = document.getElementById(prevBtnId);
    const nextMonthBtn = document.getElementById(nextBtnId);
    const currentMonthLabel = document.getElementById(currentMonthId);

    // Date→YYYY-MM形式の文字列に変換
    function getMonthString(dateObj) {
        return dateObj.getFullYear() + '-' + String(dateObj.getMonth() + 1).padStart(2, '0');
    }

    // 前月ボタンクリック時の処理
    prevMonthBtn.onclick = function() {
        const prevMonthDate = new Date(currentMonthStr + '-01');
        prevMonthDate.setMonth(prevMonthDate.getMonth() - 1);
        renderCalendar(getMonthString(prevMonthDate));
    };

    // 次月ボタンクリック時の処理
    nextMonthBtn.onclick = function() {
        const nextMonthDate = new Date(currentMonthStr + '-01');
        nextMonthDate.setMonth(nextMonthDate.getMonth() + 1);
        renderCalendar(getMonthString(nextMonthDate));
    };

    // 指定月のカレンダーを取得・描画する関数
    async function renderCalendar(targetMonth) {
        // 祝日APIから祝日データ取得
        const targetYear = targetMonth.split('-')[0];
        let holidayMap = {};
        try {
            const holidayRes = await fetch(`https://holidays-jp.github.io/api/v1/${targetYear}/date.json`);
            if (holidayRes.ok) holidayMap = await holidayRes.json();
        } catch(e) {}
        
        // 勤怠データをサーバーから取得
        try {
            const res = await window.axios.get(`/timecard?month=${targetMonth}&format=json`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = res.data;

            // summaryオブジェクトから集計値をHTMLに反映
            if (data.summary) {
                document.getElementById('required-work-days').textContent = data.summary.required_work_days ?? '-';
                document.getElementById('actual-work-days').textContent = data.summary.actual_work_days ?? '-';
                document.getElementById('overtime-minutes').textContent = data.summary.overtime_minutes ?? '-';
                document.getElementById('paid-leave-remaining').textContent = data.summary.paid_leave_remaining ?? '-';
            }

            // 日付→出勤情報のマップを作成
            const attendanceMap = {};
            data.attendances.forEach(attendance => { attendanceMap[attendance.date] = attendance; });

            // 月初・月末の日付オブジェクトを取得
            const firstDateOfMonth = new Date(data.month + '-01');
            const lastDateOfMonth = new Date(firstDateOfMonth.getFullYear(), firstDateOfMonth.getMonth() + 1, 0);

            // カレンダーテーブル作成
            let tableHtml = `<table class='w-full mt-4 text-sm table-auto border-collapse'><thead class='bg-gray-100 border-b border-gray-300 sticky top-0 z-1'><tr><th class='px-2 py-1 text-center border-table'>日付</th><th class='px-2 py-1 text-center border-table'>曜日</th><th class='px-2 py-1 text-center border-table'>出勤</th><th class='px-2 py-1 text-center border-table'>退勤</th><th class='px-2 py-1 text-center border-table'>勤務時間（分）</th><th class='px-2 py-1 text-center border-table'>在籍状態</th></tr></thead><tbody>`;
            const daysOfWeek = ['日','月','火','水','木','金','土'];
            const todayString = (new Date()).toISOString().slice(0,10);

            // 1日ごとにループして日付ごとの行を生成
            let dateObj = new Date(firstDateOfMonth);
            // 月初から月末まで1日ずつ処理する
            while (dateObj <= lastDateOfMonth) {
                const dateString = dateObj.toISOString().slice(0,10);
                const attendance = attendanceMap[dateString] || {};
                const weekDay = dateObj.getDay();

                // 各セル共通クラス
                const cellClass = 'px-2 py-1 text-center border-table';
                let rowClass = '';

                // 本日はハイライトする
                if (dateString === todayString) rowClass += ' bg-yellow-100';

                // 土曜は背景青、日曜・祝日は背景赤にする
                const isSunday = weekDay === 0;
                const isSaturday = weekDay === 6;
                const isHoliday = !!holidayMap[dateString];
                if (isSaturday) rowClass += ' bg-blue-100 text-blue-500';
                if (isSunday || isHoliday) rowClass += ' bg-pink-100 text-red-500';
                if (isSaturday || isSunday || isHoliday) rowClass += ' font-bold';

                const rowCells = [
                    `<td class='${cellClass}'>${dateString}</td>`,
                    `<td class='${cellClass}'>${daysOfWeek[weekDay]}${holidayMap[dateString] ? '・祝' : ''}</td>`,
                    `<td class='${cellClass}'>${attendance.clock_in_at ? attendance.clock_in_at.substring(11,16) : '-'}</td>`,
                    `<td class='${cellClass}'>${attendance.clock_out_at ? attendance.clock_out_at.substring(11,16) : '-'}</td>`,
                    `<td class='${cellClass}'>${attendance.working_time ?? '-'}</td>`,
                    `<td class='${cellClass}'>${attendance.status_name ?? '-'}</td>`
                ];
                tableHtml += `<tr class='${rowClass}'>${rowCells.join('')}</tr>`;
                // 日付を1日進める
                dateObj.setDate(dateObj.getDate() + 1);
            }
            tableHtml += `</tbody></table>`;
            // カレンダーエリアに描画
            calendarArea.innerHTML = tableHtml;
            // 月表示を更新
            currentMonthLabel.textContent = targetMonth;
            // 現在の月を更新
            currentMonthStr = targetMonth;
        } catch (e) {
            // console.error('カレンダーデータ取得エラー', e);
            calendarArea.innerHTML = '<div class="text-red-500">カレンダーの取得に失敗しました</div>';
        }
    }    
    // 初期表示時にカレンダーを描画
    renderCalendar(currentMonthStr);
}
