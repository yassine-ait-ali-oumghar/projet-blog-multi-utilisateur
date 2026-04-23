<template>
  <div class="max-w-3xl mx-auto animate-slide-up">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Create New Post</h1>
        <p class="text-sm text-slate-500 mt-2 font-medium">Publish a new article to the blog</p>
      </div>
      <router-link :to="{ name: 'posts.index' }" class="btn-secondary flex items-center gap-2">
        <span class="text-lg">←</span> 
        <span>Back to list</span>
      </router-link>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden relative">
      <!-- Decorative background -->
      <div class="absolute top-0 right-0 w-64 h-64 bg-primary-50 rounded-full blur-3xl opacity-50 pointer-events-none -mt-20 -mr-20"></div>

      <!-- Error Alerts -->
      <div v-if="Object.keys(errors).length > 0" class="bg-red-50 border border-red-200 p-5 m-8 rounded-xl relative z-10 shadow-sm">
        <div class="flex items-start gap-4">
          <div class="flex-shrink-0 bg-white p-2 rounded-full shadow-sm border border-red-100">
            <span class="text-red-500 text-xl">⚠️</span>
          </div>
          <div>
            <h3 class="text-base font-bold text-red-800">There were errors with your submission</h3>
            <div class="mt-2 text-sm font-medium text-red-700">
              <ul class="list-disc pl-5 space-y-1.5">
                <template v-for="(errorArray, field) in errors" :key="field">
                  <li v-for="error in errorArray" :key="error">{{ error }}</li>
                </template>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <form @submit.prevent="submitForm" class="p-8 sm:p-10 space-y-6 relative z-10">
        <!-- Title Input -->
        <div>
          <label class="block text-sm font-bold text-slate-700 mb-2">Post Title</label>
          <input 
            v-model="form.title" 
            type="text" 
            class="input-modern text-lg font-medium" 
            placeholder="Enter a captivating title..."
            autocomplete="off"
            :class="{ 'border-red-300 ring-2 ring-red-100': errors.title }"
          >
        </div>

        <!-- Description Input -->
        <div>
          <label class="block text-sm font-bold text-slate-700 mb-2">Description / Content</label>
          <textarea 
            v-model="form.description" 
            rows="8"
            class="input-modern resize-y leading-relaxed" 
            placeholder="Write the post content here..."
            :class="{ 'border-red-300 ring-2 ring-red-100': errors.description }"
          ></textarea>
        </div>

        <div class="h-px w-full bg-slate-100 my-6"></div>

        <!-- Author Select -->
        <div>
          <label class="block text-sm font-bold text-slate-700 mb-2">Post Creator</label>
          <div class="relative">
            <select 
              v-model="form.post_creator" 
              class="input-modern appearance-none cursor-pointer pr-10"
              :class="{ 'border-red-300 ring-2 ring-red-100': errors.post_creator }"
            >
              <option value="" disabled selected>Select an author...</option>
              <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }} ({{ user.email }})
              </option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
              <span class="text-xs font-bold">▼</span>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-8 mt-4 border-t border-slate-100 flex items-center justify-between">
           <router-link :to="{ name: 'posts.index' }" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors underline-offset-4 hover:underline">
            Cancel Publishing
          </router-link>
          <button 
            type="submit" 
            class="btn-primary py-3 px-8 flex items-center gap-2"
            :disabled="isSubmitting"
          >
            <span v-if="isSubmitting" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
            <span v-else class="text-lg">🚀</span>
            <span>{{ isSubmitting ? 'Publishing...' : 'Publish Post' }}</span>
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
