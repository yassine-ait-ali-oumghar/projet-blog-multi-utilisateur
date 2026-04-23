<template>
  <div class="max-w-5xl mx-auto animate-slide-up">
    <div class="bg-gradient-to-r from-primary-600 to-blue-600 rounded-2xl shadow-lg shadow-primary-500/20 p-6 mb-6 text-white">
      <h1 class="text-3xl font-black tracking-tight">Notifications Center</h1>
      <p class="text-primary-100 mt-2 text-sm">
        Likes, reposts, follows and account activity in one place.
      </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
      <div v-if="isLoading" class="p-10 flex items-center justify-center">
        <div class="w-8 h-8 border-4 border-primary-100 border-t-primary-500 rounded-full animate-spin"></div>
      </div>

      <div v-else-if="notifications.length === 0" class="p-12 text-center">
        <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center">
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"/>
            <path d="M9 17a3 3 0 0 0 6 0"/>
          </svg>
        </div>
        <p class="text-slate-700 font-bold">No notifications yet.</p>
      </div>

      <div v-else>
        <div
          v-for="item in notifications"
          :key="item.id"
          class="px-6 py-4 border-b last:border-b-0 border-slate-100 hover:bg-blue-50/50 transition-colors"
        >
          <div class="flex items-start justify-between gap-3">
            <p class="text-slate-800 text-sm font-medium">{{ item.message }}</p>
            <span class="text-xs text-slate-400 whitespace-nowrap">{{ formatDate(item.created_at) }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'AdminNotifications',
    data() {
        return {
            isLoading: true,
            notifications: []
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
        formatDate(value) {
            if (!value) return '';
            return new Intl.DateTimeFormat('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(new Date(value));
        }
    }
};
</script>
