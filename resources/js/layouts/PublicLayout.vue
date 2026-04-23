<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 font-sans flex justify-center transition-colors duration-300">
      <!-- Left Sidebar (Twitter style) -->
      <header class="w-20 xl:w-[275px] flex flex-col justify-between h-screen sticky top-0 px-2 xl:px-4 py-6 border-r border-slate-200/60 dark:border-slate-800/60 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl shrink-0">
          <div class="flex flex-col gap-2 w-full xl:items-start">
              <div class="flex items-center justify-between w-full mb-2">
                  <router-link to="/" class="flex items-center gap-4 group p-2 xl:p-3 w-fit rounded-full hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                      <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-600 via-primary-700 to-indigo-800 flex items-center justify-center text-white shadow-xl shadow-primary-500/30 group-hover:scale-105 transition-all duration-500 relative overflow-hidden">
                        <!-- Abstract Nexus Logo SVG -->
                        <svg viewBox="0 0 24 24" class="w-7 h-7 relative z-10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L3 7V17L12 22L21 17V7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 17V7L15 17V7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="drop-shadow-sm"/>
                        </svg>
                        <!-- Glow effect -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                      </div>
                      <span class="hidden xl:block text-2xl font-black tracking-tighter text-slate-900 dark:text-white uppercase">Nex<span class="text-primary-600">us</span></span>
                  </router-link>

                  <button @click="toggleDarkMode" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-xl">
                      {{ isDarkMode ? '🌙' : '☀️' }}
                  </button>
              </div>

              <!-- Navigation Links -->
              <nav class="flex flex-col gap-1 w-full xl:items-start">
                  <router-link to="/" class="flex items-center gap-5 p-3 xl:pr-6 xl:pl-4 xl:py-3 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors w-fit group" active-class="font-bold">
                      <span class="text-[26px] group-hover:scale-110 transition-transform">🏠</span>
                      <span class="hidden xl:block text-[20px] text-slate-800 dark:text-slate-200 group-hover:text-primary-600">Home</span>
                  </router-link>
                  
                  <router-link to="/explore" class="flex items-center gap-5 p-3 xl:pr-6 xl:pl-4 xl:py-3 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors w-fit group" active-class="font-bold">
                      <span class="text-[26px] group-hover:scale-110 transition-transform">🔍</span>
                      <span class="hidden xl:block text-[20px] text-slate-800 dark:text-slate-200 group-hover:text-primary-600">Explore</span>
                  </router-link>

                  <router-link to="/notifications" class="flex items-center gap-5 p-3 xl:pr-6 xl:pl-4 xl:py-3 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors w-fit group" active-class="font-bold">
                      <span class="text-[26px] group-hover:scale-110 transition-transform">🔔</span>
                      <span class="hidden xl:block text-[20px] text-slate-800 dark:text-slate-200 group-hover:text-primary-600">Notifications</span>
                  </router-link>
                  
                  <router-link v-if="user?.email === 'admin@blog.com'" to="/admin" class="flex items-center gap-5 p-3 xl:pr-6 xl:pl-4 xl:py-3 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors w-fit group">
                      <span class="text-[26px] group-hover:scale-110 transition-transform">⚙️</span>
                      <span class="hidden xl:block text-[20px] text-slate-800 dark:text-slate-200 group-hover:text-primary-600">Dashboard</span>
                  </router-link>
              </nav>

              <!-- Write Button -->
              <router-link to="/write" class="mt-4 w-12 h-12 xl:w-full bg-primary-600 hover:bg-primary-700 text-white rounded-full flex items-center justify-center xl:py-3 font-bold text-[18px] shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-0.5 mx-auto xl:mx-0">
                  <span class="xl:hidden text-xl">✏️</span>
                  <span class="hidden xl:block">Post Article</span>
              </router-link>
          </div>

          <!-- User Profile / Auth Actions -->
          <div class="w-full relative" @click.stop="toggleDropdown">
              <template v-if="!isAuthenticated">
                  <div class="flex flex-col gap-3 w-full">
                      <router-link to="/login" class="hidden xl:flex items-center justify-center w-full py-3 rounded-full border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Sign In</router-link>
                      <router-link to="/register" class="hidden xl:flex items-center justify-center w-full py-3 rounded-full bg-slate-800 dark:bg-white dark:text-slate-900 text-white font-bold hover:bg-slate-900 dark:hover:bg-slate-100 transition-colors">Sign Up</router-link>
                      <router-link to="/login" class="xl:hidden w-12 h-12 mx-auto flex items-center justify-center rounded-full bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-xl">🔐</router-link>
                  </div>
              </template>

              <template v-else>
                  <button class="flex items-center justify-center xl:justify-start gap-3 p-2 xl:p-3 w-full rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer group">
                      <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary-600 to-primary-400 text-white flex items-center justify-center font-bold text-sm shadow-md uppercase shrink-0">
                          {{ userInitials }}
                      </div>
                      <div class="text-left hidden xl:block overflow-hidden flex-1">
                          <span class="block text-sm font-bold text-slate-800 dark:text-white truncate">{{ userName }}</span>
                          <span class="block text-[13px] text-slate-500 truncate">@{{ displayUser?.name.replace(/\s+/g, '').toLowerCase() || 'user' }}</span>
                      </div>
                      <span class="hidden xl:block text-lg text-slate-400 group-hover:text-primary-500 font-bold px-2">...</span>
                  </button>

                  <!-- User Menu Dropdown -->
                  <div v-if="isDropdownOpen" class="absolute bottom-full left-0 w-full mb-2 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 py-3 animate-slide-up z-50">
                      <!-- User Stats -->
                      <div class="px-4 py-2 border-b border-slate-50 dark:border-slate-800 mb-2">
                          <div class="flex gap-4">
                              <div class="flex flex-col">
                                  <span class="text-sm font-bold text-slate-900 dark:text-white">{{ displayUser?.followers_count || 0 }}</span>
                                  <span class="text-xs text-slate-500">Followers</span>
                              </div>
                              <div class="flex flex-col">
                                  <span class="text-sm font-bold text-slate-900 dark:text-white">{{ displayUser?.likes_count || 0 }}</span>
                                  <span class="text-xs text-slate-500">Likes</span>
                              </div>
                          </div>
                      </div>
                      
                      <!-- Logout Action -->
                      <button @click="logout" class="w-full text-left px-4 py-3 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 font-bold flex items-center gap-3 transition-colors">
                          <span class="text-lg">🚪</span>
                          <span>Sign Out</span>
                      </button>
                  </div>
              </template>
          </div>
      </header>

      <!-- Main Center Content (Feed) -->
      <main class="flex-1 w-full max-w-[600px] min-h-screen border-r border-slate-200/60 dark:border-slate-800/60 bg-white dark:bg-slate-900 pb-24 sm:pb-0 transition-colors duration-300">
         <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>

      <!-- Right Sidebar (Trends/Search) -->
      <aside class="hidden lg:block w-80 h-screen sticky top-0 px-6 py-6 shrink-0">
          <!-- Search -->
          <div class="relative group">
              <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-primary-500">
                  🔍
              </span>
              <input 
                  type="text" 
                  v-model="searchQuery"
                  @input="handleSearch"
                  placeholder="Search articles or users..." 
                  class="w-full bg-slate-100 dark:bg-slate-800 border border-transparent text-slate-800 dark:text-white rounded-full focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 py-3.5 pl-12 pr-4 transition-all"
              >
          </div>

          <AiAssistantCard />

          <!-- Footer Links -->
          <nav class="flex flex-wrap gap-x-3 gap-y-1 mt-6 px-2 text-xs text-slate-500 font-medium">
              <a href="#" class="hover:underline">Terms of Service</a>
              <a href="#" class="hover:underline">Privacy Policy</a>
              <a href="#" class="hover:underline">Cookie Policy</a>
              <a href="#" class="hover:underline">Accessibility</a>
              <span>© 2026 Nexus Inc.</span>
          </nav>
      </aside>

  </div>
</template>

<script>
import axios from 'axios';
import AiAssistantCard from '../components/public/AiAssistantCard.vue';

export default {
    name: 'PublicLayout',
    components: {
        AiAssistantCard
    },
    data() {
        return {
            isDropdownOpen: false,
            searchQuery: this.$route.query.search || '',
            fullUser: null,
            isDarkMode: localStorage.getItem('darkMode') === 'true'
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
        displayUser() {
            return this.fullUser || this.user;
        },
        userName() {
            return this.displayUser ? this.displayUser.name : 'User';
        },
        userInitials() {
            if(!this.displayUser) return '?';
            return this.userName.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        }
    },
    watch: {
        '$route.query.search'(newVal) {
            this.searchQuery = newVal || '';
        },
        isDarkMode(newVal) {
            localStorage.setItem('darkMode', newVal);
            this.applyDarkMode();
        }
    },
    mounted() {
        document.addEventListener('click', this.closeDropdown);
        if (this.isAuthenticated) {
            this.fetchFullUser();
        }
        this.applyDarkMode();
    },
    beforeUnmount() {
        document.removeEventListener('click', this.closeDropdown);
    },
    methods: {
        toggleDarkMode() {
            this.isDarkMode = !this.isDarkMode;
        },
        applyDarkMode() {
            if (this.isDarkMode) {
                document.documentElement.classList.add('dark');
                document.documentElement.style.colorScheme = 'dark';
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.style.colorScheme = 'light';
            }
        },
        async fetchFullUser() {
            try {
                const response = await axios.get('/api/me');
                this.fullUser = response.data;
            } catch (err) {
                console.error("Failed to fetch full user info", err);
            }
        },
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },
        closeDropdown() {
            this.isDropdownOpen = false;
        },
        handleSearch() {
            const targetPath = (this.$route.path === '/' || this.$route.path === '/explore') 
                ? this.$route.path 
                : '/';

            this.$router.replace({
                path: targetPath,
                query: { ...this.$route.query, search: this.searchQuery || undefined }
            });
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
