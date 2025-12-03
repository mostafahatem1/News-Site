import "./bootstrap";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);

if (window.Laravel.role == "user") {
    window.Echo.private(
        "App.Models.User." + window.Laravel.userId
    ).notification((notification) => {
        const time = notification.commented_at
            ? dayjs(notification.commented_at).fromNow()
            : "now";

        // أضف الإشعار الجديد في الأعلى
        $("#pusher-notification").prepend(`
            <a class="dropdown-item" href="${notification.link}?notify=${notification.id}">
                ${notification.post_title}
                <small class="text-muted ml-2" style="font-size:11px;">
                    ${time}
                </small>
            </a>
        `);

        // إزالة رسالة "No new notifications" إذا كانت موجودة
        $("#pusher-notification .dropdown-item.text-center").remove();

        // تحديث أو إنشاء العداد
        let $count = $("#notification-count");
        if ($count.length === 0) {
            // إذا لم يوجد العداد، أنشئه وضعه بعد الجرس
            $("#notificationDropdown").append(`
                <span id="notification-count" class="badge badge-danger" style="font-size:10px;position:absolute;top:0;right:0;">1</span>
            `);
        } else {
            // إذا كان العداد موجودًا
            let count = parseInt($count.text()) || 0;
            count++;
            $count.text(count).show();
        }

        // إظهار عناصر "View all notifications" و"Delete All" إذا لم تكن موجودة
        if ($("#view-all-notifications").length === 0) {
            const csrf = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            $("#pusher-notification").after(`
                <div class="dropdown-divider"></div>
                <a id="view-all-notifications" class="dropdown-item text-center"
                    href="/account/notification">View all notifications</a>
                <form id="delete-all-notifications-form" method="POST" action="/account/notification/delete-all">
                     <input type="hidden" name="_token" value="${csrf}">
                    <button type="submit" class="dropdown-item text-center text-danger" style="font-weight:bold;">
                        Delete All
                    </button>
                </form>
            `);
        }
    });
}

if (window.Laravel.role == "admin") {
    window.Echo.private(
        "App.Models.Admin." + window.Laravel.adminId
    ).notification((notification) => {
        const time = notification.created_at
            ? dayjs(notification.created_at).fromNow()
            : "now";

        // Prepend to the notification list container
        $("#notification-list").prepend(`
            <a class="dropdown-item d-flex align-items-center" href="${
                notification.link ?? "#"
            }">
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">${time}</div>
                    <span class="font-weight-bold">${
                        notification.title ?? "New Notification"
                    }</span>
                    <div>${notification.name ?? ""}</div>
                </div>
            </a>
        `);
        // Remove "No alerts" message if present
        $(
            "#notification-list .dropdown-item.text-center.small.text-gray-500"
        ).remove();

        let $count = $("#unread-count");
        if ($count.length === 0) {
            // إذا لم يوجد العداد، أنشئه وضعه داخل رابط الجرس
            $("#alertsDropdown").append(`
                <span id="unread-count" class="badge badge-danger badge-counter">1</span>
            `);
        } else {
            // إذا كان العداد موجودًا
            let count = parseInt($count.text()) || 0;
            count++;
            $count.text(count).show();
        }
    });
}
