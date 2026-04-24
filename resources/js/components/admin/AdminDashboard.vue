<template>
  <div class="space-y-6">
    <!-- Header with Breadcrumbs and Actions -->
    <div class="flex items-center justify-between mb-8">
      <h1 class="text-2xl font-bold text-slate-800">Vue d'ensemble</h1>
    </div>

    <!-- Main Project Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <h2 class="text-lg font-bold text-slate-700">Votre Blog</h2>
      </div>
      <div class="p-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <!-- Stat Circle 1 -->
          <div class="flex flex-col items-center gap-3">
            <div class="relative w-24 h-24 flex items-center justify-center">
              <svg class="w-full h-full -rotate-90">
                <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-100" />
                <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" stroke-dasharray="251.2" :stroke-dashoffset="251.2 - (251.2 * Math.min(stats.posts, 100) / 100)" class="text-primary-500 transition-all duration-1000" />
              </svg>
              <span class="absolute text-xl font-black text-slate-800">{{ stats.posts || 0 }}</span>
            </div>
            <span class="text-xs font-bold text-slate-500 uppercase">Articles</span>
          </div>

          <!-- Stat Circle 2 -->
          <div class="flex flex-col items-center gap-3">
            <div class="relative w-24 h-24 flex items-center justify-center">
              <svg class="w-full h-full -rotate-90">
                <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-100" />
                <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" stroke-dasharray="251.2" :stroke-dashoffset="251.2 - (251.2 * Math.min(stats.users, 100) / 100)" class="text-blue-500 transition-all duration-1000" />
              </svg>
              <span class="absolute text-xl font-black text-slate-800">{{ stats.users || 0 }}</span>
            </div>
            <span class="text-xs font-bold text-slate-500 uppercase">Utilisateurs</span>
          </div>

          <!-- Stat Circle 3 -->
          <div class="flex flex-col items-center gap-3">
            <div class="relative w-24 h-24 flex items-center justify-center">
              <svg class="w-full h-full -rotate-90">
                <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-100" />
                <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" stroke-dasharray="251.2" :stroke-dashoffset="251.2 - (251.2 * Math.min(stats.likes, 100) / 100)" class="text-indigo-500 transition-all duration-1000" />
              </svg>
              <span class="absolute text-xl font-black text-slate-800">{{ stats.likes || 0 }}</span>
            </div>
            <span class="text-xs font-bold text-slate-500 uppercase">Likes</span>
          </div>

          <!-- Stat Circle 4 -->
          <div class="flex flex-col items-center gap-3">
            <div class="relative w-24 h-24 flex items-center justify-center">
              <svg class="w-full h-full -rotate-90">
                <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-100" />
                <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" stroke-dasharray="251.2" :stroke-dashoffset="251.2 - (251.2 * Math.min(stats.comments, 100) / 100)" class="text-emerald-500 transition-all duration-1000" />
              </svg>
              <span class="absolute text-xl font-black text-slate-800">{{ stats.comments || 0 }}</span>
            </div>
            <span class="text-xs font-bold text-slate-500 uppercase">Commentaires</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Banner -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 flex items-center justify-between shadow-sm">
      <div class="flex items-center gap-4">
        <div class="p-2 bg-slate-50 rounded-lg">
          <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l5 5v11a2 2 0 01-2 2z"></path></svg>
        </div>
        <p class="text-slate-600 font-medium">Vous avez <span class="font-bold text-slate-900">{{ stats.posts || 0 }}</span> articles publiés sur ce projet</p>
      </div>
      <router-link to="/admin/posts" class="px-6 py-2 rounded-full border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">Gérer les articles</router-link>
    </div>

    <!-- Two columns grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm min-h-[150px]">
        <h3 class="text-slate-800 font-bold mb-1">Articles Récents</h3>
        <p class="text-xs text-slate-400 mb-6">Dernières publications</p>
        <div class="space-y-4">
            <div v-if="recentPosts.length === 0" class="text-sm text-slate-400 italic">Aucun article récent</div>
            <div v-for="post in recentPosts" :key="post.id" class="flex items-center justify-between group">
                <span class="text-sm text-slate-600 truncate max-w-[200px]">{{ post.title }}</span>
                <span class="text-[10px] text-slate-400">{{ formatDate(post.created_at) }}</span>
            </div>
        </div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm min-h-[150px]">
        <h3 class="text-slate-800 font-bold mb-1">Croissance</h3>
        <p class="text-xs text-slate-400 mb-6">Derniers 30 jours</p>
        <div class="flex items-end justify-between gap-2 h-20">
            <div v-for="n in 12" :key="n" class="w-full bg-blue-50 rounded-t-md hover:bg-primary-100 transition-colors cursor-help" :title="'Jour ' + n" :style="{ height: (20 + Math.random() * 80) + '%' }"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      stats: {
        posts: 0,
        users: 0,
        likes: 0,
        comments: 0
      },
      recentPosts: []
    }
  },
  methods: {
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
    }
  },
  async mounted() {
    try {
      const [postsRes, usersRes] = await Promise.all([
        axios.get('/api/posts'),
        axios.get('/api/users')
      ]);
      
      const posts = postsRes.data;
      const users = usersRes.data;
      
      this.stats.posts = posts.length;
      this.stats.users = users.length;
      
      // Calculate total likes and comments from posts
      this.stats.likes = posts.reduce((acc, p) => acc + (p.likes_count || 0), 0);
      this.stats.comments = posts.reduce((acc, p) => acc + (p.comments_count || 0), 0);

      // Get 5 most recent posts
      this.recentPosts = [...posts]
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
        .slice(0, 5);
        
    } catch (error) {
      console.error('Failed to fetch stats', error);
    }
  }
}
</script>