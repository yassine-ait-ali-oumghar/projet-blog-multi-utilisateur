<template>
  <div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <div class="inline-flex items-center gap-2 mb-2 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-semibold tracking-wider uppercase border border-amber-200">
          <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
          Editing User #{{ $route.params.id }} — {{ originalName }}
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Update User Details</h1>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="!userLoaded" class="flex flex-col items-center justify-center p-12 bg-white rounded-xl shadow-sm border border-slate-200">
        <span class="text-4xl animate-bounce mb-4">⏳</span>
        <p class="text-slate-500 font-medium">Loading user data...</p>
    </div>

    <!-- Form Card -->
    <div v-else class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
      <!-- Form -->
      <form @submit.prevent="submitForm" class="p-6 space-y-6">
        
        <!-- Name Input -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
          <input 
            v-model="form.name" 
            type="text" 
            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" 
            :class="{ 'border-red-300 ring-1 ring-red-300': errors.name }"
          >
          <p v-if="errors.name" class="mt-2 text-sm text-red-600 block">{{ errors.name[0] }}</p>
        </div>

        <!-- Email Input -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
          <input 
            v-model="form.email" 
            type="email" 
            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" 
            :class="{ 'border-red-300 ring-1 ring-red-300': errors.email }"
          >
          <p v-if="errors.email" class="mt-2 text-sm text-red-600 block">{{ errors.email[0] }}</p>
        </div>

        <!-- Password Input (Optional) -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Change Password (Optional)</label>
          <div class="relative">
             <input 
               v-model="form.password" 
               :type="showPassword ? 'text' : 'password'" 
               class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 pr-10 transition-colors" 
               placeholder="Leave blank to keep current password"
               :class="{ 'border-red-300 ring-1 ring-red-300': errors.password }"
             >
             <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
               {{ showPassword ? '🚫' : '👁' }}
             </button>
          </div>
          <p class="mt-1 flex items-center gap-1 text-xs text-slate-400">If changing, requires at least 6 characters.</p>
          <p v-if="errors.password" class="mt-2 text-sm text-red-600 block">{{ errors.password[0] }}</p>
        </div>

        <!-- Actions -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
           <router-link :to="{ name: 'users.show', params: { id: $route.params.id } }" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
            Cancel
          </router-link>
          
          <button 
            type="submit" 
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg shadow-sm transition-all flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
            :disabled="isSubmitting"
          >
            <span v-if="isSubmitting" class="animate-spin inline-block">⏳</span>
            <span v-else>💾</span>
            {{ isSubmitting ? 'Saving...' : 'Update Records' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'UserEdit',
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: ''
            },
            originalName: '',
            errors: {},
            isSubmitting: false,
            userLoaded: false,
            showPassword: false
        }
    },
    mounted() {
        this.fetchUser();
    },
    methods: {
        async fetchUser() {
            try {
                const id = this.$route.params.id;
                const response = await axios.get(`/api/users/${id}`);
                const user = response.data;
                this.originalName = user.name;
                this.form.name = user.name;
                this.form.email = user.email;
                this.userLoaded = true;
            } catch (error) {
                console.error("Error fetching user data:", error);
                this.$router.push({ name: 'users.index' });
            }
        },
        async submitForm() {
            this.isSubmitting = true;
            this.errors = {};
            try {
                const id = this.$route.params.id;
                await axios.put(`/api/users/${id}`, this.form);
                this.$router.push({ name: 'users.show', params: { id } });
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error("Error updating user:", error);
                }
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>
