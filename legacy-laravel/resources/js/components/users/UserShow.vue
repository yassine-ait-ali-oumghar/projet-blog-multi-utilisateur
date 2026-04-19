<template>
  <div class="max-w-4xl mx-auto pb-12">
    <!-- Header Controls -->
    <div class="mb-6 flex items-center justify-between">
      <router-link :to="{ name: 'users.index' }" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors flex items-center gap-2">
        <span class="text-lg">←</span> Back to Users
      </router-link>
      <div v-if="user" class="flex items-center gap-3">
         <router-link :to="{ name: 'users.edit', params: { id: user.id } }" class="bg-amber-100 hover:bg-amber-200 text-amber-800 text-sm font-medium py-1.5 px-4 rounded-full transition-colors flex items-center gap-2 border border-amber-200">
           ✏️ Edit User
         </router-link>
         <button @click="deleteUser" class="bg-red-100 hover:bg-red-200 text-red-800 text-sm font-medium py-1.5 px-4 rounded-full transition-colors flex items-center gap-2 border border-red-200">
           🗑 Delete User
         </button>
      </div>
    </div>

    <!-- Main Content Area -->
    <div v-if="user" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
      <!-- Profile Header -->
      <div class="px-8 py-10 bg-gradient-to-r from-slate-800 to-slate-700 text-white flex items-center gap-6">
         <div class="w-24 h-24 rounded-full text-white flex items-center justify-center text-4xl font-bold shadow-lg shrink-0 border-4 border-white/20" :class="getColorHash(user.name)">
             {{ getInitials(user.name) }}
         </div>
         <div>
             <h1 class="text-3xl font-bold mb-2">{{ user.name }}</h1>
             <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6 text-slate-300 text-sm">
                 <span class="flex items-center gap-2">✉️ {{ user.email }}</span>
                 <span class="hidden sm:inline">•</span>
                 <span class="flex items-center gap-2">🗓 Joined {{ formatDate(user.created_at) }}</span>
                 <span class="hidden sm:inline">•</span>
                 <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-400"></span> Active Access</span>
             </div>
         </div>
      </div>

      <!-- Published Posts Section -->
      <div class="p-8">
        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
            📝 Posts by this user
            <span class="bg-blue-100 text-blue-700 py-0.5 px-2.5 rounded-full text-xs font-semibold">{{ user.posts.length }}</span>
        </h3>
        
        <div v-if="user.posts.length > 0" class="grid gap-4 sm:grid-cols-2">
            <div 
               v-for="post in user.posts" 
               :key="post.id" 
               class="p-5 border border-slate-200 rounded-xl hover:border-blue-300 hover:shadow-md transition-all group bg-slate-50 hover:bg-white"
            >
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors line-clamp-1">{{ post.title }}</h4>
                    <span class="text-xs text-slate-400 shrink-0">{{ formatShortDate(post.created_at) }}</span>
                </div>
                <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ post.description }}</p>
                <router-link :to="{ name: 'posts.show', params: { id: post.id } }" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    Read article <span>→</span>
                </router-link>
            </div>
        </div>
        
        <div v-else class="text-center py-12 bg-slate-50 rounded-xl border border-slate-200 border-dashed">
             <div class="text-4xl mb-3">📭</div>
             <p class="text-slate-500 font-medium">This user has no posts yet.</p>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-else class="flex flex-col items-center justify-center p-20 bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-4"></div>
        <p class="text-slate-500 font-medium">Loading user profile...</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'UserShow',
    data() {
        return {
            user: null
        }
    },
    mounted() {
        this.fetchUser();
    },
    methods: {
        async fetchUser() {
            try {
                const id = this.$route.params.id;
                const response = await axios.get(`/api/users/${id}`);
                this.user = response.data;
            } catch(error) {
                console.error("Error fetching user data:", error);
            }
        },
        async deleteUser() {
            if(confirm("Are you really sure you want to completely erase this user ?")) {
                try {
                    await axios.delete(`/api/users/${this.user.id}`);
                    this.$router.push({ name: 'users.index' });
                } catch(error) {
                    console.error("Error deleting user:", error);
                }
            }
        },
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric', month: 'long', day: 'numeric'
            }).format(date);
        },
        formatShortDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('en-US', {
                month: 'short', day: 'numeric'
            }).format(date);
        },
        getInitials(name) {
            if (!name) return '?';
            return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        },
        getColorHash(name) {
            if (!name) return 'bg-slate-500';
            const colors = ['bg-blue-500', 'bg-emerald-500', 'bg-purple-500', 'bg-rose-500', 'bg-amber-500', 'bg-indigo-500'];
            const sum = name.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0);
            return colors[sum % colors.length];
        }
    }
}
</script>
