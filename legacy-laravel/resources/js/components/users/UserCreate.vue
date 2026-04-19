<template>
  <div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">Add New User</h1>
        <p class="text-sm text-slate-500 mt-1">Register a new author or admin</p>
      </div>
      <router-link :to="{ name: 'users.index' }" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
        ← Back to users
      </router-link>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
      <!-- Form -->
      <form @submit.prevent="submitForm" class="p-6 space-y-6">
        
        <!-- Name Input -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
          <input 
            v-model="form.name" 
            type="text" 
            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" 
            placeholder="John Doe"
            autocomplete="off"
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
            placeholder="john@example.com"
            autocomplete="off"
            :class="{ 'border-red-300 ring-1 ring-red-300': errors.email }"
          >
          <p v-if="errors.email" class="mt-2 text-sm text-red-600 block">{{ errors.email[0] }}</p>
        </div>

        <!-- Password Input -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
          <div class="relative">
             <input 
               v-model="form.password" 
               :type="showPassword ? 'text' : 'password'" 
               class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 pr-10 transition-colors" 
               placeholder="Min 6 characters"
               :class="{ 'border-red-300 ring-1 ring-red-300': errors.password }"
             >
             <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
               {{ showPassword ? '🚫' : '👁' }}
             </button>
          </div>
          <p v-if="errors.password" class="mt-2 text-sm text-red-600 block">{{ errors.password[0] }}</p>
        </div>

        <!-- Confirm Password Input -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password</label>
          <input 
            v-model="form.password_confirmation" 
            :type="showPassword ? 'text' : 'password'" 
            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" 
            placeholder="Retype password"
          >
          <p v-if="passwordMismatch" class="mt-2 text-sm text-red-600 block">Passwords do not match</p>
        </div>

        <!-- Actions -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
           <router-link :to="{ name: 'users.index' }" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
            Cancel
          </router-link>
          <button 
            type="submit" 
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg shadow-sm transition-all flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
            :disabled="isSubmitting || passwordMismatch"
          >
            <span v-if="isSubmitting" class="animate-spin inline-block">⏳</span>
            <span v-else>✅</span>
            {{ isSubmitting ? 'Creating...' : 'Create User' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'UserCreate',
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            errors: {},
            isSubmitting: false,
            showPassword: false
        }
    },
    computed: {
        passwordMismatch() {
            if(this.form.password && this.form.password_confirmation) {
               return this.form.password !== this.form.password_confirmation;
            }
            return false;
        }
    },
    methods: {
        async submitForm() {
            if(this.passwordMismatch) return;
            
            this.isSubmitting = true;
            this.errors = {};
            try {
                await axios.post('/api/users', this.form);
                this.$router.push({ name: 'users.index' });
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error("Error creating user:", error);
                }
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>
