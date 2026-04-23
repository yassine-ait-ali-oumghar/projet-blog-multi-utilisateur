<template>
  <div class="max-w-3xl mx-auto animate-slide-up">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Add New User</h1>
        <p class="text-sm text-slate-500 mt-2 font-medium">Register a new author or administrator</p>
      </div>
      <router-link :to="{ name: 'users.index' }" class="btn-secondary flex items-center gap-2">
        <span class="text-lg">←</span> 
        <span>Back to users</span>
      </router-link>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden relative">
      <!-- Decorative background -->
      <div class="absolute top-0 right-0 w-64 h-64 bg-primary-50 rounded-full blur-3xl opacity-50 pointer-events-none -mt-20 -mr-20"></div>
      
      <!-- Form -->
      <form @submit.prevent="submitForm" class="p-8 sm:p-10 space-y-6 relative z-10">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Name Input -->
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
            <input 
              v-model="form.name" 
              type="text" 
              class="input-modern" 
              placeholder="John Doe"
              autocomplete="off"
              :class="{ 'border-red-300 ring-2 ring-red-100': errors.name }"
            >
            <p v-if="errors.name" class="mt-2 text-sm font-medium text-red-600 block">{{ errors.name[0] }}</p>
          </div>

          <!-- Email Input -->
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
            <input 
              v-model="form.email" 
              type="email" 
              class="input-modern" 
              placeholder="john@example.com"
              autocomplete="off"
              :class="{ 'border-red-300 ring-2 ring-red-100': errors.email }"
            >
            <p v-if="errors.email" class="mt-2 text-sm font-medium text-red-600 block">{{ errors.email[0] }}</p>
          </div>
        </div>

        <div class="h-px w-full bg-slate-100 my-6"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Password Input -->
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
            <div class="relative">
               <input 
                 v-model="form.password" 
                 :type="showPassword ? 'text' : 'password'" 
                 class="input-modern pr-12" 
                 placeholder="Min 6 characters"
                 :class="{ 'border-red-300 ring-2 ring-red-100': errors.password }"
               >
               <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary-600 transition-colors">
                 <span class="text-xl">{{ showPassword ? '🚫' : '👁' }}</span>
               </button>
            </div>
            <p v-if="errors.password" class="mt-2 text-sm font-medium text-red-600 block">{{ errors.password[0] }}</p>
          </div>

          <!-- Confirm Password Input -->
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Confirm Password</label>
            <input 
              v-model="form.password_confirmation" 
              :type="showPassword ? 'text' : 'password'" 
              class="input-modern" 
              placeholder="Retype password"
            >
            <p v-if="passwordMismatch" class="mt-2 text-sm font-medium text-red-600 block">Passwords do not match</p>
          </div>
        </div>

        <!-- Actions -->
        <div class="pt-8 mt-4 border-t border-slate-100 flex items-center justify-between">
           <router-link :to="{ name: 'users.index' }" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors underline-offset-4 hover:underline">
            Cancel Registration
          </router-link>
          <button 
            type="submit" 
            class="btn-primary py-3 px-8 flex items-center gap-2"
            :disabled="isSubmitting || passwordMismatch"
          >
            <span v-if="isSubmitting" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
            <span v-else class="text-lg">✅</span>
            <span>{{ isSubmitting ? 'Creating User...' : 'Create User' }}</span>
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
