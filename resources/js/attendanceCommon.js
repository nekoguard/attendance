/**
 * 本日の打刻状態を取得し、各欄にセットする
 * @param {Object} mapping - { clock_in_at: 'clockIn', clock_out_at: 'clockOut', ... }
 * @param {Object} [labelMap] - { clock_in_at: '出勤', ... }（省略時は日本語ラベル）
 */
export function setTodayAttendance(mapping, labelMap = null) {
    const defaultLabels = {
        clock_in_at: '出勤',
        clock_out_at: '退勤',
        break_start_at: '外出',
        break_end_at: '戻り',
    };
    axios.get('/api/attendance/today')
        .then(res => {
            const att = res.data;
            Object.entries(mapping).forEach(([field, elId]) => {
                if (att && att[field]) {
                    const el = document.getElementById(elId);
                    if (el) {
                        const date = new Date(att[field].replace(' ', 'T'));
                        const label = (labelMap && labelMap[field]) || defaultLabels[field] || '';
                        el.textContent = label + ' ' + date.toLocaleTimeString('ja-JP', { hour: '2-digit', minute: '2-digit', hour12: false });
                    }
                }
            });
        });
}
// 勤怠打刻・日時表示などの共通関数をまとめたモジュール
import axios from 'axios';

// CSRFトークンをaxios全リクエストに自動付与
const tokenMeta = document.querySelector('meta[name="csrf-token"]');
if (tokenMeta) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenMeta.content;
}

/**
 * サーバー時刻を取得し、指定要素に1秒ごとに表示する
 * @param {string} elementId - 時刻を表示する要素のID
 */
export function setupServerTime(elementId) {
    const timeEl = document.getElementById(elementId);
    if (!timeEl) return;

    axios.get('/api/time')
        .then(res => {
            let serverTime = new Date(res.data.time.replace(' ', 'T'));
            // 1秒ごとに時刻を更新
            const updateTime = () => {
                serverTime.setSeconds(serverTime.getSeconds() + 1);
                timeEl.textContent = serverTime.toLocaleString('ja-JP', { hour12: false });
            };
            updateTime();
            setInterval(updateTime, 1000);
        });
}

/**
 * 勤怠打刻APIを呼び出し、成功時は指定要素に時刻を表示・アラート
 * @param {string} field - 打刻種別（clock_in_at等）
 * @param {string} divId - 日時を表示する要素のID
 * @param {string} label - アラート表示用のラベル
 */
export async function handleAttendance(field, divId, label) {
    // APIリクエスト用ヘッダー
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };
    const defaultLabels = {
        clock_in_at: '出勤',
        clock_out_at: '退勤',
        break_start_at: '外出',
        break_end_at: '戻り',
    };
    try {
        const res = await axios.post('/attendances/stamp', { field }, { headers });
        // 返却データから最初に見つかった打刻日時を取得
        const dateFields = ['clock_in_at', 'clock_out_at', 'break_start_at', 'break_end_at'];
        const dateStr = dateFields.map(f => res.data[f]).find(v => !!v);
        // 取得した日時が存在する場合
        if (res.data && dateStr) {
            const div = document.getElementById(divId);
            if (div) {
                let date = new Date(dateStr.replace(' ', 'T'));
                // ラベル付きで表示（例: 出勤 09:00）
                const showLabel = label || defaultLabels[field] || '';
                div.textContent = showLabel + ' ' + date.toLocaleTimeString('ja-JP', { hour: '2-digit', minute: '2-digit', hour12: false });
            }
        }
        alert((label || defaultLabels[field] || '') + 'が記録されました');
    } catch (e) {
        // console.log(e);
        alert((label || defaultLabels[field] || '') + '登録に失敗しました');
    }
}
