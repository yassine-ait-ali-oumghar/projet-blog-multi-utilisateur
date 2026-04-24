<template>
  <div class="flex h-screen overflow-hidden bg-[#f0f4f9] font-sans">
    <!-- 1. Left Nav Rail (Icons Only) -->
    <aside class="w-[70px] bg-white border-r border-slate-200 flex flex-col items-center py-4 shrink-0 z-30 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center text-white mb-8 shadow-lg shadow-primary-500/20">
        <svg viewBox="0 0 24 24" class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2L3 7V17L12 22L21 17V7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M9 17V7L15 17V7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <nav class="flex flex-col gap-6 items-center">
        <router-link to="/admin" class="p-3 rounded-xl transition-all" :class="$route.path === '/admin' ? 'bg-primary-50 text-primary-600 shadow-inner' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
        </router-link>
        <router-link to="/admin/posts" class="p-3 rounded-xl transition-all" :class="$route.path.startsWith('/admin/posts') ? 'bg-primary-50 text-primary-600 shadow-inner' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l5 5v11a2 2 0 01-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3v5h5M16 13H8M16 17H8M10 9H8"></path></svg>
        </router-link>
        <router-link to="/admin/users" class="p-3 rounded-xl transition-all" :class="$route.path.startsWith('/admin/users') ? 'bg-primary-50 text-primary-600 shadow-inner' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </router-link>
        <router-link to="/admin/notifications" class="p-3 rounded-xl transition-all" :class="$route.path.startsWith('/admin/notifications') ? 'bg-primary-50 text-primary-600 shadow-inner' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a3 3 0 0 0 6 0"></path></svg>
        </router-link>
      </nav>

      <div class="mt-auto flex flex-col gap-4 items-center mb-6">
        <button @click="logout" class="p-3 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        </button>
        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 border border-slate-200 uppercase">
          {{ userInitials }}
        </div>
      </div>
    </aside>

    <!-- 2. Middle Secondary Sidebar -->
    <aside class="w-[260px] bg-white border-r border-slate-200 flex flex-col shrink-0 z-20">
      <div class="p-4 space-y-4">
        <!-- New Post Button -->
        <router-link to="/admin/posts/create" class="w-full bg-gradient-to-r from-primary-500 to-indigo-600 hover:from-primary-600 hover:to-indigo-700 text-white font-bold py-2.5 rounded-lg shadow-md shadow-primary-500/20 flex items-center justify-center gap-2 transition-all">
          <span class="text-xl">+</span>
          <span class="text-sm">Nouvel article</span>
        </router-link>

        <!-- Search Bar -->
        <div class="relative">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
          </span>
          <input type="text" placeholder="Rechercher..." class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 pl-9 pr-4 text-xs focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
        </div>
      </div>

      <!-- Navigation Tree -->
      <nav class="flex-1 overflow-y-auto px-2 py-2 space-y-1">
        <router-link to="/admin" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm font-bold transition-all" :class="$route.path === '/admin' ? 'bg-primary-600 text-white shadow-md shadow-primary-500/20' : 'text-slate-600 hover:bg-slate-50'">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            Vue d'ensemble
          </span>
        </router-link>

        <div class="mt-6 px-3 mb-2 flex items-center justify-between group cursor-pointer">
           <div class="flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest">
              Gestion du contenu
           </div>
        </div>
        
        <router-link to="/admin/posts" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 transition-all" :class="$route.path.startsWith('/admin/posts') ? 'bg-slate-100 text-primary-700 font-bold' : ''">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l5 5v11a2 2 0 01-2 2z"></path></svg>
          Articles
        </router-link>

        <router-link to="/admin/users" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 transition-all" :class="$route.path.startsWith('/admin/users') ? 'bg-slate-100 text-primary-700 font-bold' : ''">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
          Utilisateurs
        </router-link>

        <router-link to="/admin/notifications" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 transition-all" :class="$route.path.startsWith('/admin/notifications') ? 'bg-slate-100 text-primary-700 font-bold' : ''">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a3 3 0 0 0 6 0"></path></svg>
          Notifications
        </router-link>

        <div class="mt-6 px-3 mb-2 flex items-center justify-between group cursor-pointer">
           <div class="flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest">
              Paramètres
           </div>
        </div>
        
        <div class="px-3 py-2 flex items-center gap-2 text-sm font-medium text-slate-400 cursor-not-allowed italic">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
          Configuration (bientôt)
        </div>
      </nav>
    </aside>

    <!-- 3. Main Content Area -->
    <main class="flex-1 overflow-y-auto p-10 bg-[#f0f4f9]">
       <div class="max-w-6xl mx-auto">
          <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
              <component :is="Component" />
            </transition>
          </router-view>
       </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      hasUnreadNotifications: false
    }
  },
  computed: {
    user() {
      const userStr = localStorage.getItem('user');
      return userStr ? JSON.parse(userStr) : null;
    },
    userName() {
      return this.user ? this.user.name : 'Admin';
    },
    userInitials() {
      if(!this.user) return 'A';
      return this.userName.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    }
  },
  methods: {
    async logout() {
      try {
        await axios.post('/api/logout');
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        this.$router.push('/login');
      } catch (error) {
        console.error('Logout failed', error);
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        this.$router.push('/login');
      }
    }
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>