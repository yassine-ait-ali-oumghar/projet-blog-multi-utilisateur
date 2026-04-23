<template>
  <div class="max-w-3xl mx-auto pb-12">
    <!-- Header Controls -->
    <div class="mb-6 flex items-center justify-between">
      <router-link :to="{ name: 'posts.index' }" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors flex items-center gap-2">
        <span class="text-lg">←</span> Back to Posts
      </router-link>
      <div v-if="post" class="flex items-center gap-3">
         <router-link :to="{ name: 'posts.edit', params: { id: post.id } }" class="bg-amber-100 hover:bg-amber-200 text-amber-800 text-sm font-medium py-1.5 px-4 rounded-full transition-colors flex items-center gap-2 border border-amber-200">
           ✏️ Edit Article
         </router-link>
      </div>
    </div>

    <!-- Main Content Area -->
    <div v-if="post" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
      <!-- Article Header -->
      <div class="px-8 py-10 bg-gradient-to-b from-slate-50 to-white border-b border-slate-100">
        <h1 class="text-4xl font-extrabold text-slate-800 mb-6 leading-tight">{{ post.title }}</h1>
        <div class="flex items-center gap-4 text-sm text-slate-500">
           <div class="flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-1.5 rounded-full border border-blue-100">
              <span class="font-medium text-blue-800">{{ post.user ? post.user.name : 'Unknown Author' }}</span>
           </div>
           <span>•</span>
           <span class="flex items-center gap-1">📅 {{ formatDate(post.created_at) }}</span>
        </div>
      </div>

      <!-- Article Body -->
      <div class="p-8 prose prose-slate max-w-none text-slate-700 leading-relaxed text-lg whitespace-pre-wrap break-all overflow-hidden">
        {{ post.description }}
      </div>

      <!-- Author Bio Section -->
      <div class="bg-slate-50 border-t border-slate-100 p-8">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">About the Author</h3>
        <div class="flex items-start gap-4">
          <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl font-bold shadow-md shrink-0">
            {{ getInitials(post.user ? post.user.name : '?') }}
          </div>
          <div>
            <h4 class="text-xl font-bold text-slate-800">{{ post.user ? post.user.name : 'Unknown Author' }}</h4>
            <div class="mt-1 flex flex-col gap-1 text-sm text-slate-500">
              <span class="flex items-center gap-2">✉️ {{ post.user ? post.user.email : 'No email available' }}</span>
              <span class="flex items-center gap-2">⭐ Member since {{ post.user ? formatYear(post.user.created_at) : 'N/A' }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-else class="flex flex-col items-center justify-center p-20 bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-4"></div>
        <p class="text-slate-500 font-medium">Fetching article...</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PostShow',
    data() {
        return {
            post: null
        }
    },
    mounted() {
        this.fetchPost();
    },
    methods: {
        async fetchPost() {
            try {
                const id = this.$route.params.id;
                const response = await axios.get(`/api/posts/${id}`);
                this.post = response.data;
            } catch(error) {
                console.error("Error fetching post data:", error);
            }
        },
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric', month: 'long', day: 'numeric',
                hour: '2-digit', minute: '2-digit'
            }).format(date);
        },
        formatYear(dateString) {
            if (!dateString) return '';
            return new Date(dateString).getFullYear();
        },
        getInitials(name) {
            if (!name || name === '?') return '?';
            return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        }
    }
}
</script>
