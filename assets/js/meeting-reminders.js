document.addEventListener('DOMContentLoaded', function() {
    function checkMeetingReminders() {
        const url = window.location.origin + '/SushobhaCRM/modules/prospects/check_reminders_ajax.php';
        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && data.meetings) {
                    // Use server time to avoid client clock drift issues
                    const serverTime = new Date(data.server_time.replace(' ', 'T'));
                    
                    data.meetings.forEach(m => {
                        const meetingTime = new Date(m.followup_date.replace(' ', 'T'));
                        const diffMs = meetingTime - serverTime;
                        const diffMins = diffMs / 60000;

                        // 5 Hour Reminder (Between 4.5 and 5 hours, show only once)
                        if (diffMins <= 300 && diffMins > 270) {
                            const key5h = 'reminder_5h_' + m.id;
                            if (!localStorage.getItem(key5h)) {
                                showReminder(m, 5, 'Upcoming Meeting in 5 Hours', 10000);
                                localStorage.setItem(key5h, 'true');
                            }
                        }

                        // 1 Hour Reminder (Between 0 and 1 hour)
                        if (diffMins <= 60 && diffMins > 0) {
                            const key1h = 'reminder_1h_' + m.id;
                            const snoozeKey = 'snooze_1h_' + m.id;
                            
                            const hasShown = localStorage.getItem(key1h);
                            const snoozeUntil = localStorage.getItem(snoozeKey);
                            const canShowSnooze = !snoozeUntil || (new Date() > new Date(parseInt(snoozeUntil)));

                            if (!hasShown || canShowSnooze) {
                                // Clear the snooze if it's expired so it doesn't immediately re-trigger later
                                if (snoozeUntil && canShowSnooze) {
                                    localStorage.removeItem(snoozeKey);
                                }
                                
                                // Show persistent reminder with snooze button
                                showReminder(m, 1, 'Upcoming Meeting in 1 Hour!', null);
                                localStorage.setItem(key1h, 'true');
                            }
                        }
                    });
                }
            })
            .catch(err => console.error('Meeting Reminder Fetch Error:', err));
    }

    function showReminder(m, type, title, hideAfterMs) {
        // Prevent duplicate toasts for the same meeting and type
        if (document.getElementById(`toast_meeting_${type}_${m.id}`)) return;

        const container = document.getElementById('toastPlacement');
        if (!container) return;

        const is1Hour = type === 1;
        const autoHideAttr = hideAfterMs ? `data-bs-autohide="true" data-bs-delay="${hideAfterMs}"` : `data-bs-autohide="false"`;

        const snoozeBtnHtml = is1Hour ? `<button type="button" class="btn btn-sm btn-outline-warning mt-2" onclick="snoozeReminder(${m.id})"><i class="bi bi-clock-history me-1"></i>Snooze 15m</button>` : '';

        const toastHtml = `
            <div id="toast_meeting_${type}_${m.id}" class="toast shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" ${autoHideAttr}>
                <div class="toast-header ${is1Hour ? 'bg-danger' : 'bg-primary'} text-white">
                    <i class="bi bi-calendar-event me-2"></i>
                    <strong class="me-auto">${title}</strong>
                    <small>${new Date(m.followup_date.replace(' ', 'T')).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</small>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <strong>Lead:</strong> ${m.company_name || m.lead_code}<br>
                    <strong>With:</strong> ${m.meeting_with || 'Not specified'}<br>
                    <strong>Purpose:</strong> ${m.purpose || m.type}
                    <div class="mt-3 pt-2 border-top d-flex gap-2">
                        <a href="${window.location.origin}/SushobhaCRM/modules/prospects/view.php?id=${m.lead_id}" class="btn btn-sm btn-primary mt-2"><i class="bi bi-eye me-1"></i>View Lead</a>
                        ${snoozeBtnHtml}
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', toastHtml);
        const toastEl = document.getElementById(`toast_meeting_${type}_${m.id}`);
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
        
        // Remove from DOM after hidden to keep DOM clean
        toastEl.addEventListener('hidden.bs.toast', function () {
            toastEl.remove();
        });
    }

    window.snoozeReminder = function(meetingId) {
        const snoozeKey = 'snooze_1h_' + meetingId;
        // Snooze for 15 minutes
        localStorage.setItem(snoozeKey, Date.now() + (15 * 60 * 1000));
        
        // Hide the current toast
        const toastEl = document.getElementById(`toast_meeting_1_${meetingId}`);
        if (toastEl) {
            const toast = bootstrap.Toast.getInstance(toastEl);
            if (toast) toast.hide();
        }
    };

    // Run immediately on page load, then every 30 seconds
    checkMeetingReminders();
    setInterval(checkMeetingReminders, 30000);
});
