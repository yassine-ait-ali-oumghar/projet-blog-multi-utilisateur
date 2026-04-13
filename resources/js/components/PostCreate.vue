<template>
  <div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">Create New Post</h1>
        <p class="text-sm text-slate-500 mt-1">Publish a new article to the blog</p>
      </div>
      <router-link :to="{ name: 'posts.index' }" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
        ← Back to list
      </router-link>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
      <!-- Error Alerts -->
      <div v-if="Object.keys(errors).length > 0" class="bg-red-50 border-l-4 border-red-500 p-4 m-6 rounded-r-md">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <span class="text-red-500">⚠️</span>
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
            <div class="mt-2 text-sm text-red-700">
              <ul class="list-disc pl-5 space-y-1">
                <template v-for="(errorArray, field) in errors" :key="field">
                  <li v-for="error in errorArray" :key="error">{{ error }}</li>
                </template>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <form @submit.prevent="submitForm" class="p-6 space-y-6">
        <!-- Title Input -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Post Title</label>
          <input 
            v-model="form.title" 
            type="text" 
            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" 
            placeholder="Enter a captivating title..."
            autocomplete="off"
            :class="{ 'border-red-300 ring-1 ring-red-300': errors.title }"
          >
        </div>

        <!-- Description Input -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Description / Content</label>
          <textarea 
            v-model="form.description" 
            rows="5"
            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors resize-y" 
            placeholder="Write the post content here..."
            :class="{ 'border-red-300 ring-1 ring-red-300': errors.description }"
          ></textarea>
        </div>

        <!-- Author Select -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Post Creator</label>
          <div class="relative">
            <select 
              v-model="form.post_creator" 
              class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 appearance-none transition-colors cursor-pointer"
              :class="{ 'border-red-300 ring-1 ring-red-300': errors.post_creator }"
            >
              <option value="" disabled selected>Select an author...</option>
              <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }} ({{ user.email }})
              </option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
              ▼
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-slate-100 flex justify-end">
          <button 
            type="submit" 
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg shadow-sm transition-all flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
            :disabled="isSubmitting"
          >
            <span v-if="isSubmitting" class="animate-spin inline-block">⏳</span>
            <span v-else>🚀</span>
            {{ isSubmitting ? 'Publishing...' : 'Publish Post' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PostCreate',
    data() {
        return {
            form: {
                title: '',
                description: '',
                post_creator: ''
            },
            users: [],
            errors: {},
            isSubmitting: false
        }
    },
    mounted() {
        this.fetchUsers();
    },
    methods: {
        async fetchUsers() {
            try {
                const response = await axios.get('/api/users');
                this.users = response.data;
            } catch (error) {
                console.error("Error fetching users:", error);
            }
        },
        async submitForm() {
            this.isSubmitting = true;
            this.errors = {};
            try {
                await axios.post('/api/posts', this.form);
                this.$router.push({ name: 'posts.index' });
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error("Error creating post:", error);
                }
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>
