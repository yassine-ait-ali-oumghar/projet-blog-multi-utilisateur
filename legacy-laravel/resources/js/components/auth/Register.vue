<template>
  <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 mb-4 text-2xl">
        👋
      </div>
      <h1 class="text-2xl font-bold text-slate-800">Create an Account</h1>
      <p class="text-sm text-slate-500 mt-1">Sign up to start publishing posts.</p>
    </div>

    <!-- Error Alert -->
    <div v-if="Object.keys(errors).length > 0" class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 text-sm text-red-700">
        Review the errors below.
    </div>

    <form @submit.prevent="handleRegister" class="space-y-4">
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Full Name</label>
        <input 
          v-model="form.name" 
          type="text" 
          class="w-full bg-slate-50 border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" 
          placeholder="Jane Doe"
        >
        <span class="text-xs text-red-500" v-if="errors.name">{{ errors.name[0] }}</span>
      </div>

      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address</label>
        <input 
          v-model="form.email" 
          type="email" 
          class="w-full bg-slate-50 border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" 
          placeholder="jane@example.com"
        >
        <span class="text-xs text-red-500" v-if="errors.email">{{ errors.email[0] }}</span>
      </div>

      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
        <input 
          v-model="form.password" 
          type="password" 
          class="w-full bg-slate-50 border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" 
          placeholder="Min 6 characters"
        >
        <span class="text-xs text-red-500" v-if="errors.password">{{ errors.password[0] }}</span>
      </div>

      <button 
        type="submit" 
        class="w-full bg-slate-800 hover:bg-slate-900 text-white font-medium py-3 px-4 rounded-lg shadow-sm transition-colors mt-4"
        :disabled="isLoading"
      >
        <span v-if="isLoading">Processing...</span>
        <span v-else>Register & Login</span>
      </button>
    </form>
    
    <div class="mt-6 text-center text-sm text-slate-500">
       Already have an account? 
       <router-link to="/login" class="font-semibold text-blue-600 hover:text-blue-800">Sign In</router-link>
       <br><br>
       <router-link to="/" class="font-medium text-slate-400 hover:text-slate-600">← Back to Home</router-link>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Register',
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: ''
            },
            errors: {},
            isLoading: false
        }
    },
    methods: {
        async handleRegister() {
            this.isLoading = true;
            this.errors = {};
            try {
                const response = await axios.post('/api/register', this.form);
                const { access_token, user } = response.data;
                
                localStorage.setItem('token', access_token);
                localStorage.setItem('user', JSON.stringify(user));
                axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;

                // Normal user redirects back to public layout!
                window.location.href = '/';
            } catch (error) {
                if(error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>
