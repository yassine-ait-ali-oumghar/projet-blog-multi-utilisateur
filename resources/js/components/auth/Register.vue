<template>
  <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-none animate-slide-up max-w-md w-full mx-auto relative overflow-hidden transition-colors duration-300">
    <!-- Decorative elements -->
    <div class="absolute -top-16 -right-16 w-32 h-32 bg-primary-500/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-16 -left-16 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl"></div>

    <div class="relative text-center mb-10 z-10">
      <!-- Professional Logo Icon -->
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-600 to-indigo-700 text-white mb-6 shadow-xl shadow-primary-500/20 transform hover:scale-110 transition-transform duration-500">
        <svg viewBox="0 0 24 24" class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 4h4l8 11V4h4v16h-4l-8-11v11H4V4z"/>
        </svg>
      </div>
      <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase">Nex<span class="text-primary-600">us</span></h1>
      <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Rejoignez la communauté Nexus</p>
    </div>

    <!-- Error Alert -->
    <div v-if="Object.keys(errors).length > 0" class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4 text-sm text-red-700 dark:text-red-400 flex items-center gap-3 relative z-10">
        <span class="text-lg">⚠️</span>
        <span class="font-bold">Veuillez vérifier les erreurs ci-dessous.</span>
    </div>

    <form @submit.prevent="handleRegister" class="space-y-5 relative z-10">
      <div>
        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 ml-1">Nom Complet</label>
        <div class="relative group">
          <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-primary-500 transition-colors">👤</span>
          <input 
            v-model="form.name" 
            type="text" 
            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block p-4 pl-12 text-[15px] transition-all placeholder-slate-400 outline-none" 
            placeholder="Jean Dupont"
          >
        </div>
        <span class="text-xs text-red-500 font-bold mt-1.5 ml-1 block" v-if="errors.name">{{ errors.name[0] }}</span>
      </div>

      <div>
        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 ml-1">Adresse Email</label>
        <div class="relative group">
          <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-primary-500 transition-colors">📧</span>
          <input 
            v-model="form.email" 
            type="email" 
            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block p-4 pl-12 text-[15px] transition-all placeholder-slate-400 outline-none" 
            placeholder="jean@exemple.com"
          >
        </div>
        <span class="text-xs text-red-500 font-bold mt-1.5 ml-1 block" v-if="errors.email">{{ errors.email[0] }}</span>
      </div>

      <div>
        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 ml-1">Mot de passe</label>
        <div class="relative group">
          <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-primary-500 transition-colors">🔒</span>
          <input 
            v-model="form.password" 
            type="password" 
            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block p-4 pl-12 text-[15px] transition-all placeholder-slate-400 outline-none" 
            placeholder="6 caractères minimum"
          >
        </div>
        <span class="text-xs text-red-500 font-bold mt-1.5 ml-1 block" v-if="errors.password">{{ errors.password[0] }}</span>
      </div>

      <button 
        type="submit" 
        class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-primary-500/30 transition-all active:scale-[0.98] disabled:opacity-50 flex justify-center items-center gap-2 mt-2"
        :disabled="isLoading"
      >
        <span v-if="isLoading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
        <span>{{ isLoading ? 'Création...' : 'Créer mon compte' }}</span>
      </button>
    </form>
    
    <div class="mt-10 text-center text-sm text-slate-500 dark:text-slate-400 relative z-10 border-t border-slate-100 dark:border-slate-800 pt-8">
       Déjà un compte ? 
       <router-link to="/login" class="font-bold text-primary-600 hover:text-primary-800 dark:hover:text-primary-400 transition-colors">Se connecter</router-link>
       
       <div class="mt-6">
           <router-link to="/" class="inline-flex items-center gap-2 font-bold text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 transition-all bg-slate-50 dark:bg-slate-800 px-5 py-2.5 rounded-full border border-slate-200 dark:border-slate-700 hover:border-primary-200 dark:hover:border-primary-800 shadow-sm">
             <span>←</span> Retour à l'accueil
           </router-link>
       </div>
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
