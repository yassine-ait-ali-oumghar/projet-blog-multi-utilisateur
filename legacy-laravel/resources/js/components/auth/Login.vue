<template>
  <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-600 mb-4 text-2xl">
        🔐
      </div>
      <h1 class="text-2xl font-bold text-slate-800">Welcome Back</h1>
      <p class="text-sm text-slate-500 mt-1">Please enter your credentials to log in.</p>
    </div>

    <!-- Error Alert -->
    <div v-if="errorMessage" class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 text-sm text-red-700">
        {{ errorMessage }}
    </div>

    <form @submit.prevent="handleLogin" class="space-y-5">
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address</label>
        <input 
          v-model="form.email" 
          type="email" 
          required
          class="w-full bg-slate-50 border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" 
          placeholder="you@example.com"
        >
      </div>

      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
        <input 
          v-model="form.password" 
          type="password" 
          required
          class="w-full bg-slate-50 border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" 
          placeholder="••••••••"
        >
      </div>

      <button 
        type="submit" 
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg shadow-sm transition-colors mt-2"
        :disabled="isLoading"
      >
        <span v-if="isLoading">Authenticating...</span>
        <span v-else>Sign In to Dashboard</span>
      </button>
    </form>
    
    <div class="mt-6 text-center text-sm text-slate-500">
       Don't have an account? 
       <router-link to="/register" class="font-semibold text-blue-600 hover:text-blue-800">Create one</router-link>
       <br><br>
       <router-link to="/" class="font-medium text-slate-400 hover:text-slate-600">← Back to Home</router-link>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Login',
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            errorMessage: '',
            isLoading: false
        }
    },
    methods: {
        async handleLogin() {
            this.isLoading = true;
            this.errorMessage = '';
            try {
                const response = await axios.post('/api/login', this.form);
                const { access_token, user } = response.data;
                
                // Store authentication dynamically
                localStorage.setItem('token', access_token);
                localStorage.setItem('user', JSON.stringify(user));
                
                // Setup default bearing auth to bypass refresh checks temporarily
                axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;

                // Divert based on role/email 
                if (user.email === 'admin@blog.com') {
                    window.location.href = '/admin';
                } else {
                    window.location.href = '/';
                }
            } catch (error) {
                if(error.response && error.response.status === 422) {
                    this.errorMessage = Object.values(error.response.data.errors)[0][0];
                } else {
                    this.errorMessage = "Une erreur serveur est survenue.";
                }
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>
