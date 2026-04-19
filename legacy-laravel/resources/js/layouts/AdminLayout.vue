<template>
  <div class="flex h-screen overflow-hidden bg-slate-50">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-800 text-white flex flex-col shadow-xl z-20 shrink-0">
      <div class="p-6 border-b border-slate-700 flex items-center gap-3">
        <span class="text-2xl">📝</span>
        <h1 class="text-xl font-bold tracking-wider">Blog Admin</h1>
      </div>
      <nav class="flex-1 px-4 py-6 space-y-2">
        <router-link to="/admin" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors hover:bg-slate-700 hover:text-blue-400" active-class="bg-blue-600 text-white hover:text-white hover:bg-blue-600" exact>
          <span class="text-lg">📊</span>
          <span class="font-medium">Dashboard</span>
        </router-link>
        <router-link to="/admin/posts" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors hover:bg-slate-700 hover:text-blue-400" active-class="bg-blue-600 text-white hover:text-white hover:bg-blue-600" :class="{ 'bg-blue-600 text-white hover:text-white': $route.path.startsWith('/admin/posts') }">
          <span class="text-lg">📄</span>
          <span class="font-medium">Posts</span>
        </router-link>
        <router-link to="/admin/users" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors hover:bg-slate-700 hover:text-blue-400" active-class="bg-blue-600 text-white hover:text-white hover:bg-blue-600" :class="{ 'bg-blue-600 text-white hover:text-white': $route.path.startsWith('/admin/users') }">
          <span class="text-lg">👥</span>
          <span class="font-medium">Users</span>
        </router-link>
      </nav>
      <div class="p-4 border-t border-slate-700">
         <button @click="logout" class="w-full flex items-center justify-center gap-2 bg-slate-700 hover:bg-red-600 text-white py-2 rounded-lg transition-colors text-sm font-medium">
             <span>🚪</span> Log out
         </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden relative">
      <!-- Top Header -->
      <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200 h-16 flex items-center justify-between px-8 z-10 shrink-0">
        <div class="text-slate-500 font-medium flex items-center gap-2">
           <span class="text-slate-300">/</span> Administration Console
        </div>
        <div class="flex items-center gap-4 hover:bg-slate-50 p-1 pr-3 rounded-full transition-colors border border-transparent hover:border-slate-200">
          <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm uppercase">
            {{ userInitials }}
          </div>
          <span class="text-sm font-semibold text-slate-700">{{ userName }}</span>
        </div>
      </header>

      <!-- Page Content -->
      <div class="flex-1 overflow-y-auto p-8">
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
export default {
  name: 'AdminLayout',
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
  methods: {
      logout() {
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          this.$router.push('/login');
      }
  }
}
</script>
