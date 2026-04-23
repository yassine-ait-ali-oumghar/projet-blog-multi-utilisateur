<template>
  <div class="w-full">
    <div class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200/60 dark:border-slate-800/60">
      <div class="flex items-center px-4 h-14">
        <h1 class="text-xl font-black text-slate-900 dark:text-white">Notifications</h1>
      </div>
    </div>

    <div v-if="isLoading" class="flex justify-center p-12">
      <div class="w-10 h-10 border-4 border-primary-100 dark:border-primary-900 border-t-primary-500 rounded-full animate-spin"></div>
    </div>

    <div v-else-if="notifications.length === 0" class="p-8 text-center max-w-sm mx-auto mt-12">
      <div class="text-6xl mb-6">🔔</div>
      <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-2">Aucune notification</h3>
      <p class="text-slate-500 dark:text-slate-400 text-[15px]">Quand quelqu'un aime ou reposte vos publications, vous le verrez ici.</p>
    </div>

    <div v-else class="flex flex-col">
      <div
        v-for="notification in notifications"
        :key="notification.id"
        class="border-b border-slate-200/60 dark:border-slate-800/60 p-4 hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors flex gap-4"
        @click="goToPost(notification)"
        :class="{ 'cursor-pointer': notification.post_id }"
      >
        <div class="shrink-0 pt-1">
          <span v-if="notification.type === 'like'" class="text-xl text-pink-500">❤️</span>
          <span v-else-if="notification.type === 'repost'" class="text-xl text-green-500">🔁</span>
          <span v-else-if="notification.type === 'follow'" class="text-xl text-blue-500">👤</span>
          <span v-else class="text-xl">🔔</span>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-[15px] text-slate-900 dark:text-slate-200 font-medium leading-normal">{{ notification.message }}</p>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ formatShortDate(notification.created_at) }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Notifications',
    data() {
        return {
            notifications: [],
            isLoading: true
        };
    },
    mounted() {
        this.fetchNotifications();
    },
    methods: {
        async fetchNotifications() {
            try {
                const response = await axios.get('/api/notifications');
                this.notifications = response.data;
            } catch (error) {
                console.error('Failed to fetch notifications', error);
            } finally {
                this.isLoading = false;
            }
        },
        goToPost(notification) {
            if (notification.post_id) {
                this.$router.push({ name: 'posts.show', params: { id: notification.post_id } });
            }
        },
        formatShortDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMins / 60);
            const diffDays = Math.floor(diffHours / 24);

            if (diffMins < 60) return `${diffMins}m`;
            if (diffHours < 24) return `${diffHours}h`;
            if (diffDays < 7) return `${diffDays}d`;

            return new Intl.DateTimeFormat('fr-FR', {
                month: 'short', day: 'numeric'
            }).format(date);
        }
    }
};
</script>
