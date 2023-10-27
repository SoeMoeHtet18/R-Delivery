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
            </ul>
        </div>
  </div>
</template>

<script>
import { Icon } from '@iconify/vue';

export default {
	components: {
		Icon
	},
    data() {
        return {
            isOpen: false,
            notifications: [],
        }
    },
    methods: {
        toggleNotifications() {
            this.isOpen = !this.isOpen;
        },
        closeNotifications() {
            this.isOpen = false;
        },
        async getNotifications() {
            const response = await fetch('/api/notifications');
            const data = await response.json();

            const formattedData = data.data.map((notification, index) => {
                let message = ''; // Declare message outside the if block and initialize it.
                if (
                    notification.title == 'payment channel confirm' ||
                    notification.title == 'payable or not' ||
                    notification.title == 'order create by shop' ||
                    notification.title == 'collection create by shop' ||
                    notification.title == 'order delay by rider' ||
                    notification.title == 'order cancel request by rider'
                ) {
                    let parts = notification.message.split(';');
                    message = parts[0].trim();
                }
                return {
                    'id': notification.id,
                    'title': notification.title,
                    'message': message,
                    'isRead': notification.pivot.is_read
                };
            });

            this.notifications = formattedData;
        },
        makeNotificationRead(notification) {
            notification.isRead = 1;
        }
    },
    mounted() {
        this.getNotifications();
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