<template>
  <div class="bg-slate-900 p-8 rounded-2xl shadow-2xl border border-slate-700 max-w-md mx-auto relative overflow-hidden">
    <!-- Stylistic background pulse -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 via-amber-500 to-red-500"></div>

    <div class="text-center mb-8 relative z-10">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-800 text-red-500 mb-4 text-3xl shadow-inner border border-slate-700">
        🛡️
      </div>
      <h1 class="text-2xl font-bold text-white tracking-wide uppercase">Admin Login</h1>
      <p class="text-sm text-slate-400 mt-2">Restricted Area. Authorized personnel only.</p>
    </div>

    <!-- Error Alert -->
    <div v-if="errorMessage" class="mb-6 bg-red-900/50 border border-red-500/50 rounded-lg p-4 text-sm text-red-200 flex items-start gap-3">
        <span class="text-red-400 mt-0.5">⚠️</span>
        {{ errorMessage }}
    </div>

    <form @submit.prevent="handleAdminLogin" class="space-y-6 relative z-10">
      <div>
        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Security Clearance (Email)</label>
        <input 
          v-model="form.email" 
          type="email" 
          required
          class="w-full bg-slate-800 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 block p-3.5 transition-colors placeholder-slate-500" 
          placeholder="admin@blog.com"
        >
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Passcode</label>
        <input 
          v-model="form.password" 
          type="password" 
          required
          class="w-full bg-slate-800 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 block p-3.5 transition-colors placeholder-slate-500" 
          placeholder="••••••••"
        >
      </div>

      <button 
        type="submit" 
        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 px-4 rounded-lg shadow-lg shadow-red-900/20 transition-all active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-2"
        :disabled="isLoading"
      >
        <span v-if="isLoading">Verifying Clearance...</span>
        <span v-else>Authorize Access <span>🔐</span></span>
      </button>
    </form>
    
    <div class="mt-8 text-center text-sm text-slate-500 border-t border-slate-800 pt-6">
       Not an administrator? 
       <router-link to="/login" class="font-semibold text-slate-300 hover:text-white transition-colors">Standard Login</router-link>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'AdminLogin',
    data() {
        return {
            form: {
                email: 'admin@blog.com', // Pre-filled for demo purposes
                password: 'admin123'
            },
            errorMessage: '',
            isLoading: false
        }
    },
    methods: {
        async handleAdminLogin() {
            this.isLoading = true;
            this.errorMessage = '';
            try {
                const response = await axios.post('/api/admin-login', this.form);
                const { access_token, user } = response.data;
                
                localStorage.setItem('token', access_token);
                localStorage.setItem('user', JSON.stringify(user));
                
                axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;

                // Redirect strictly to Admin Dashboard
                window.location.href = '/admin';
            } catch (error) {
                if(error.response && error.response.status === 422) {
                    this.errorMessage = Object.values(error.response.data.errors)[0][0];
                } else {
                    this.errorMessage = "Une erreur système est survenue. Connexion refusée.";
                }
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>
