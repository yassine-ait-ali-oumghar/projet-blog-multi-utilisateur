<template>
  <div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">Posts Management</h1>
        <p class="text-sm text-slate-500 mt-1">
          Showing <span class="font-semibold text-blue-600">{{ filteredPosts.length }}</span> total posts
        </p>
      </div>

      <div class="flex items-center gap-4 w-full sm:w-auto">
        <!-- Search Bar -->
        <div class="relative w-full sm:w-64">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            🔍
          </span>
          <input 
            v-model="searchQuery" 
            type="text" 
            class="w-full bg-white border border-slate-300 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow shadow-sm"
            placeholder="Search by title..."
          >
        </div>

        <router-link :to="{ name: 'posts.create' }" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors flex items-center gap-2 whitespace-nowrap">
          <span>➕</span> Create Post
        </router-link>
      </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider border-b border-slate-200">
              <th class="px-6 py-4 font-semibold"># ID</th>
              <th class="px-6 py-4 font-semibold">Title</th>
              <th class="px-6 py-4 font-semibold">Author</th>
              <th class="px-6 py-4 font-semibold">Created At</th>
              <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
            <tr 
              v-for="(post, index) in filteredPosts" 
              :key="post.id" 
              class="hover:bg-slate-50 transition-colors"
              :class="{ 'bg-slate-50/50': index % 2 !== 0 }"
            >
              <td class="px-6 py-4 font-medium text-slate-500">#{{ post.id }}</td>
              <td class="px-6 py-4 font-medium text-slate-800">{{ post.title }}</td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  {{ post.user ? post.user.name : 'Unknown' }}
                </span>
              </td>
              <td class="px-6 py-4 text-slate-500">{{ formatDate(post.created_at) }}</td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <router-link :to="{ name: 'posts.show', params: { id: post.id } }" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors tooltip-trigger" title="View details">
                    👁
                  </router-link>
                  <router-link :to="{ name: 'posts.edit', params: { id: post.id } }" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-md transition-colors" title="Edit post">
                    ✏️
                  </router-link>
                  <button @click="deletePost(post.id)" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Delete post">
                    🗑
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredPosts.length === 0">
              <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                <div class="text-3xl mb-2">📭</div>
                <p>No posts found matching your criteria.</p>
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
