<template>
  <div class="max-w-6xl mx-auto animate-slide-up">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60">
      <div>
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Posts Management</h1>
        <p class="text-sm text-slate-500 mt-2">
          Showing <span class="font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded-md">{{ filteredPosts.length }}</span> total posts
        </p>
      </div>

      <div class="flex items-center gap-4 w-full sm:w-auto">
        <!-- Search Bar -->
        <div class="relative w-full sm:w-72">
          <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
            🔍
          </span>
          <input 
            v-model="searchQuery" 
            type="text" 
            class="input-modern pl-11 py-2.5"
            placeholder="Search by title..."
          >
        </div>

        <router-link :to="{ name: 'posts.create' }" class="btn-primary py-2.5 px-5 flex items-center gap-2 whitespace-nowrap">
          <span class="text-lg">➕</span> 
          <span>Create Post</span>
        </router-link>
      </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-200/80">
              <th class="px-6 py-4"># ID</th>
              <th class="px-6 py-4">Title</th>
              <th class="px-6 py-4">Author</th>
              <th class="px-6 py-4">Created At</th>
              <th class="px-6 py-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
            <tr 
              v-for="(post, index) in filteredPosts" 
              :key="post.id" 
              class="hover:bg-primary-50/50 transition-colors group"
            >
              <td class="px-6 py-4 font-bold text-slate-400 group-hover:text-primary-500 transition-colors">#{{ post.id }}</td>
              <td class="px-6 py-4 font-bold text-slate-800 group-hover:text-primary-700 transition-colors">{{ post.title }}</td>
              <td class="px-6 py-4">
                <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                  {{ post.user ? post.user.name : 'Unknown' }}
                </span>
              </td>
              <td class="px-6 py-4 text-slate-500 font-medium">{{ formatDate(post.created_at) }}</td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
                  <router-link :to="{ name: 'posts.show', params: { id: post.id } }" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-100 rounded-xl transition-colors tooltip-trigger" title="View details">
                    👁
                  </router-link>
                  <router-link :to="{ name: 'posts.edit', params: { id: post.id } }" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-100 rounded-xl transition-colors" title="Edit post">
                    ✏️
                  </router-link>
                  <button @click="deletePost(post.id)" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Delete post">
                    🗑
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredPosts.length === 0">
              <td colspan="5" class="px-6 py-16 text-center text-slate-500">
                <div class="text-4xl mb-4 animate-bounce">📭</div>
                <p class="text-lg font-bold text-slate-700">No posts found matching your criteria.</p>
                <p class="text-sm text-slate-400 mt-1">Try adjusting your search terms.</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PostIndex',
    data() {
        return {
            posts: [],
            searchQuery: ''
        }
    },
    computed: {
        filteredPosts() {
            if (!this.searchQuery) return this.posts;
            const query = this.searchQuery.toLowerCase();
            return this.posts.filter(post => 
                post.title.toLowerCase().includes(query) || 
                (post.user && post.user.name.toLowerCase().includes(query))
            );
        }
    },
    mounted() {
        this.fetchPosts();
    },
    methods: {
        async fetchPosts() {
            try {
                const response = await axios.get('/api/posts');
                this.posts = response.data;
            } catch (error) {
                console.error("Error fetching posts:", error);
            }
        },
        async deletePost(id) {
            if(confirm("Are you sure you want to delete this post? This action cannot be undone.")) {
                try {
                    await axios.delete(`/api/posts/${id}`);
                    this.fetchPosts();
                } catch(error) {
                    console.error("Error deleting post:", error);
                }
            }
        },
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric', month: 'short', day: 'numeric'
            }).format(date);
        }
    }
}
</script>
