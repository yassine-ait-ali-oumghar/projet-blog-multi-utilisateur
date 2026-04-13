<template>
  <div class="min-h-screen flex flex-col bg-slate-50">
      <!-- Public Navbar -->
      <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm sticky top-0 z-50">
          <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
              <router-link to="/" class="flex items-center gap-2 group">
                  <span class="text-2xl group-hover:rotate-12 transition-transform duration-300">✨</span>
                  <span class="text-xl font-bold tracking-tight text-[#0f172a]">Public Blog</span>
              </router-link>
              <div class="flex items-center gap-4 relative">
                  <!-- Guest State -->
                  <template v-if="!isAuthenticated">
                    <router-link to="/login" class="text-sm font-semibold text-slate-600 hover:text-[#2563eb] px-3 py-2 border border-slate-200 rounded-lg transition-all">Sign In</router-link>
                    <router-link to="/register" class="bg-gradient-to-r from-[#2563eb] to-[#1d4ed8] hover:scale-105 text-white text-sm font-bold py-2 px-5 rounded-lg transition-all shadow-md shadow-blue-200/50">Sign Up</router-link>
                  </template>

                  <!-- Authenticated User Widget -->
                  <div v-else class="relative" @click.stop="toggleDropdown">
                      <button class="flex items-center gap-3 p-1 pr-3 rounded-full hover:bg-slate-100 transition-colors border border-transparent hover:border-slate-200 cursor-pointer">
                          <div class="w-9 h-9 rounded-full bg-[#2563eb] text-white flex items-center justify-center font-bold text-sm shadow-sm uppercase">
                              {{ userInitials }}
                          </div>
                          <span class="text-sm font-bold text-[#0f172a] hidden sm:block">{{ userName }}</span>
                          <span class="text-xs text-slate-400">▼</span>
                      </button>

                      <!-- Dropdown Menu -->
                      <transition name="fade">
                          <div v-if="isDropdownOpen" class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                              <router-link to="/write" class="flex items-center gap-2 px-4 py-3 hover:bg-slate-50 text-sm font-semibold text-slate-700 w-full text-left transition-colors">
                                  <span class="text-lg">✏️</span> Écrire un article
                              </router-link>
                              <div class="h-px bg-slate-100 mx-4 my-1"></div>
                              <button @click="logout" class="flex items-center gap-2 px-4 py-3 hover:bg-red-50 text-sm font-semibold text-red-600 w-full text-left transition-colors">
                                  <span class="text-lg">🚪</span> Se déconnecter
                              </button>
                          </div>
                      </transition>
                  </div>
              </div>
          </div>
      </nav>

      <!-- Main Content -->
      <main class="flex-1 w-full max-w-6xl mx-auto p-6 md:p-8">
         <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>

      <footer class="bg-white border-t border-slate-200 py-10 text-center mt-auto">
          <p class="text-slate-500 font-medium">© 2026 <span class="text-[#0f172a] font-bold">Public Blog</span> · Tous droits réservés</p>
          <div class="flex justify-center gap-6 mt-4 opacity-50">
              <span class="text-xl">🕊️</span>
              <span class="text-xl">🐙</span>
              <span class="text-xl">📸</span>
          </div>
      </footer>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PublicLayout',
    data() {
        return {
            isDropdownOpen: false
        }
    },
    computed: {
        isAuthenticated() {
            return !!localStorage.getItem('token');
        },
        user() {
            const userStr = localStorage.getItem('user');
            return userStr ? JSON.parse(userStr) : null;
        },
        userName() {
            return this.user ? this.user.name : 'User';
        },
        userInitials() {
            if(!this.user) return '?';
            return this.userName.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        }
    },
    mounted() {
        document.addEventListener('click', this.closeDropdown);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.closeDropdown);
    },
    methods: {
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },
        closeDropdown() {
            this.isDropdownOpen = false;
        },
        async logout() {
            try {
                await axios.post('/api/logout');
            } catch (err) {
                console.error("Logout request failed or already invalidated");
            } finally {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                this.$router.push('/login');
            }
        }
    }
}
</script>
