<template>
    <div class="relative">
        <Icon id="notification" icon="mingcute:notification-fill" color="#116a5b" width="20" height="24" 
            @click="toggleNotifications"
        />
        <div v-if="isOpen" id="notification-list"
            class="absolute top-10 right-0 bg-white rounded shadow-lg pb-4"
        >
            <div class="flex justify-between items-center py-4 border-b border-solid border-main px-5">
                <h1 class="title">NOTIFICATION</h1>
                <iconify-icon icon="gg:close" style="color: #aaa;" width="20" @click="closeNotifications"></iconify-icon>
            </div>
            <ul v-for="(notification, index) in notifications" :key="index">
                <li v-if="notification.title != 'csv'" class="notification"
                    :class="{'bg-info-main': !notification.isRead, 'bg-white': notification.isRead}"
                    @click="makeNotificationRead(notification)"
                >
                    {{ notification.message }}
                </li>
                <li v-else>No Notification</li>
            </ul>
        </div>
  </div>
</template>

<script>
import { Icon } from '@iconify/vue';
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css';

export default {
	components: {
		Icon
	},
    data() {
        return {
            isOpen: false,
            notifications: [],
            notificationLatestTime: null,
        }
    },
    methods: {
        toggleNotifications() {
            this.isOpen = !this.isOpen;
        },
        closeNotifications() {
            this.isOpen = false;
        },
        showNotification(notifications) {
            notifications.forEach((notification) => {
                let options = {
                    text: this.getNotificationText(notification),
                    duration: 3000, // 10 seconds
                    close: true,
                    gravity: "top", // Position the notification at the top
                    className: "toastify-notification",
                    // You can customize the appearance with additional CSS classes
                };

                Toastify(options).showToast();
            });
        },
        getNotificationText(notification) {
            let message = '';
            if (this.isRelevantNotification(notification.title)) {
                let parts = notification.message.split(';');
                message = parts[0].trim();
            }
            return message;
        },
        async getNotifications() {
            const response = await fetch('/api/notifications');
            const data = await response.json();

            this.notificationLatestTime = data.data.latest_time;
            console.log('noti latest', this.notificationLatestTime);
            const formattedNotifications = this.formatNotifications(data.data);
            if(formattedNotifications) {
                this.notifications = formattedNotifications;
            }
        },
        async getNewNotifications() {
            const url = `/api/new-notifications`;
            const requestData = { 'latest_time': this.notificationLatestTime };
            const data = await this.sendRequestWithData(url, 'POST', requestData);
            console.log('new noti latest', this.notificationLatestTime);
            const formattedNotifications = this.formatNotifications(data.data);
            if(formattedNotifications.length > 0) {
                this.notificationLatestTime = data.data.latest_time;
                this.showNotification(formattedNotifications);
            }
        },
        async makeNotificationRead(notification) {
            notification.isRead = 1;
            const notificationId = notification.id;
            
            const url = `/api/notifications/${notificationId}/read`;

            await this.sendRequestWithoutData(url, 'PUT');
        },
        formatNotifications(data) {
            if (data) {
                const notifications = Object.values(data).filter(item => typeof item === 'object'); // Convert data to an array of notifications
                return notifications.map((notification) => {
                    let message = this.getNotificationText(notification);
                    return {
                        'id': notification.id,
                        'title': notification.title,
                        'message': message,
                        'isRead': notification.pivot.is_read,
                    };
                });
            } else {
                return []; // Return an empty array when data is empty
            }
        },
        isRelevantNotification(title) {
            const relevantTitles = [
                'payment channel confirm',
                'payable or not',
                'order create by shop',
                'collection create by shop',
                'order delay by rider',
                'order cancel request by rider',
            ];
            return relevantTitles.includes(title);
        },
        async sendRequestWithoutData(url, method) {
            const csrfToken = document.head.querySelector('meta[name="_token"]').content;
            const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            });
            return await response.json();
        },
        async sendRequestWithData(url, method, requestData) {
            const csrfToken = document.head.querySelector('meta[name="_token"]').content;
            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData),
            });
            return await response.json();
        },
    },
    mounted() {
        this.getNotifications();
        setInterval(() => {
            this.getNewNotifications();
        }, 5000); // Every 5 seconds
        setInterval(() => {
            this.getNotifications();
        }, 10000); // Every 10 seconds
    }

};

</script>

<style>
#notification {
    cursor: pointer;
}

#notification-list {
    min-width: 28vw;
    max-width: 35vw;
    border-radius: 10px;
    background: #FFF;
    box-shadow: 0px 15px 10px 15px rgba(0, 0, 0, 0.50);
}

#notification-list .title {
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    color: #000000;
}

.notification {
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 17px;
    border-bottom: 1px solid var(--border, #D9D9D9);
    padding: 14px 20px;
}
</style>