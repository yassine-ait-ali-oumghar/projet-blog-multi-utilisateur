<template>
  <div class="flex h-screen overflow-hidden bg-slate-100 font-sans">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-900 text-slate-300 flex flex-col shadow-2xl z-20 shrink-0 transition-all duration-300">
      <div class="p-6 border-b border-slate-800/80 flex items-center gap-3 bg-slate-950/70">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-blue-500 flex items-center justify-center text-white shadow-lg shadow-primary-500/20">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
            <path d="M14 3v6h6"/>
            <path d="M8 13h8M8 17h5"/>
          </svg>
        </div>
        <h1 class="text-xl font-black text-white tracking-wide">Blog<span class="text-primary-500">Admin</span></h1>
      </div>
      
      <div class="px-6 py-4">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Main Menu</p>
      </div>

      <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto custom-scrollbar">
        <router-link to="/admin/posts" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white group" active-class="bg-primary-600 text-white shadow-lg shadow-primary-600/20" :class="{ 'bg-primary-600 text-white shadow-lg shadow-primary-600/20': $route.path.startsWith('/admin/posts') }">
          <span class="text-lg group-hover:scale-110 transition-transform">📄</span>
          <span class="font-semibold text-sm">Posts Management</span>
        </router-link>
        
        <router-link to="/admin/users" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white group" active-class="bg-primary-600 text-white shadow-lg shadow-primary-600/20" :class="{ 'bg-primary-600 text-white shadow-lg shadow-primary-600/20': $route.path.startsWith('/admin/users') }">
          <span class="text-lg group-hover:scale-110 transition-transform">👥</span>
          <span class="font-semibold text-sm">Users Directory</span>
        </router-link>

        <router-link to="/admin/notifications" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white group" active-class="bg-primary-600 text-white shadow-lg shadow-primary-600/20" :class="{ 'bg-primary-600 text-white shadow-lg shadow-primary-600/20': $route.path.startsWith('/admin/notifications') }">
          <svg class="w-5 h-5 text-slate-300 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"/>
            <path d="M9 17a3 3 0 0 0 6 0"/>
          </svg>
          <span class="font-semibold text-sm">Notifications</span>
        </router-link>
      </nav>
      
      <div class="p-4 border-t border-slate-800 bg-slate-950/50">
         <button @click="logout" class="w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-red-500/10 hover:text-red-500 hover:border-red-500/30 border border-transparent text-slate-300 py-2.5 rounded-xl transition-all duration-300 text-sm font-bold">
             <span>🚪</span> Log out
         </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden relative">
      <!-- Top Header -->
      <header class="bg-white/90 backdrop-blur-xl shadow-sm border-b border-slate-200 h-16 flex items-center justify-between px-8 z-10 shrink-0 transition-all duration-300">
        <div class="flex items-center gap-4">
           <div class="text-slate-400 font-semibold text-sm flex items-center gap-2">
              <span class="text-primary-500">Dashboard</span>
              <span class="text-slate-300">/</span>
              <span class="text-slate-700 capitalize">{{ $route.name ? $route.name.replace('admin.', '').replace('.', ' ') : 'Overview' }}</span>
           </div>
        </div>

        <div class="flex items-center gap-5">
          <button @click="$router.push('/admin/notifications')" class="relative p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-full transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"/>
              <path d="M9 17a3 3 0 0 0 6 0"/>
            </svg>
            <span v-if="hasUnreadNotifications" class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
          </button>

          <div class="h-8 w-px bg-slate-200"></div>

          <div class="flex items-center gap-3 cursor-pointer group">
            <div class="text-right hidden md:block">
              <p class="text-sm font-bold text-slate-700 group-hover:text-primary-600 transition-colors">{{ userName }}</p>
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Administrator</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-primary-600 to-primary-400 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-primary-500/30 uppercase ring-2 ring-white group-hover:ring-primary-100 transition-all">
              {{ userInitials }}
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <div class="flex-1 overflow-y-auto p-8 bg-slate-50/50 relative">
        <!-- Dashboard Background elements -->
        <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-white to-transparent pointer-events-none"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto">
          <router-view v-slot="{ Component }">
            <transition name="slide-fade" mode="out-in">
              <component :is="Component" />
            </transition>
          </router-view>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  name: 'AdminLayout',
  data() {
      return {
          hasUnreadNotifications: false
      };
  },
  computed: {
      userName() {
          const userStr = localStorage.getItem('user');
          if(userStr) return JSON.parse(userStr).name;
          return 'Admin';
      },
      userInitials() {
          const name = this.userName;
          if(name === 'Admin') return 'AD';
          return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0,2);
      }
  },
  mounted() {
      this.fetchNotificationState();
  },
  methods: {
      async fetchNotificationState() {
          try {
              const token = localStorage.getItem('token');
              if (!token) return;
              const response = await fetch('/api/notifications', {
                  headers: {
                      Authorization: `Bearer ${token}`,
                      Accept: 'application/json'
                  }
              });
              if (!response.ok) return;
              const notifications = await response.json();
              this.hasUnreadNotifications = Array.isArray(notifications) && notifications.length > 0;
          } catch (error) {
              this.hasUnreadNotifications = false;
          }
      },
      logout() {
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          this.$router.push('/login');
      }
  }
}
</script>
