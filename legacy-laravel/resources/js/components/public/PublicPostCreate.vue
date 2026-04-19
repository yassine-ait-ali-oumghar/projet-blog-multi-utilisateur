<template>
  <div class="max-w-3xl mx-auto py-8">
    <div class="mb-8 border-b border-slate-200 pb-6 flex items-center justify-between">
      <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
        <span>✏️</span> Rédiger un nouvel article
      </h1>
      <router-link to="/" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
        ← Annuler
      </router-link>
    </div>

    <!-- Error Alert -->
    <div v-if="Object.keys(errors).length > 0" class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-600">
        Veuillez corriger les erreurs ci-dessous.
    </div>

    <form @submit.prevent="publishArticle" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">
      
      <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Titre de l'article</label>
        <input 
          v-model="form.title" 
          type="text" 
          class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-4 text-lg transition-colors placeholder-slate-400" 
          placeholder="Ex: Les meilleures pratiques de Vue.js"
        >
        <span class="text-xs font-semibold text-red-500 mt-1 block" v-if="errors.title">{{ errors.title[0] }}</span>
      </div>

      <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Contenu / Description</label>
        <textarea 
          v-model="form.description" 
          rows="10" 
          class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-4 transition-colors placeholder-slate-400 resize-y" 
          placeholder="Partagez vos idées au monde entier..."
        ></textarea>
        <span class="text-xs font-semibold text-red-500 mt-1 block" v-if="errors.description">{{ errors.description[0] }}</span>
      </div>

      <div class="pt-4 flex justify-end">
          <button 
            type="submit" 
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-indigo-600/30 transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2"
            :disabled="isSubmitting"
          >
            <span v-if="isSubmitting">Publication en cours...</span>
            <span v-else>Publier l'article 🚀</span>
          </button>
      </div>

    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'PublicPostCreate',
    data() {
        return {
            form: {
                title: '',
                description: '',
                post_creator: '' // Will be set before submit
            },
            errors: {},
            isSubmitting: false
        }
    },
    methods: {
        async publishArticle() {
            this.isSubmitting = true;
            this.errors = {};
            
            try {
                // Fetch current user logic directly from localStorage without inputs
                const userStr = localStorage.getItem('user');
                if(!userStr) {
                    this.$router.push('/login');
                    return;
                }
                const currentUser = JSON.parse(userStr);
                this.form.post_creator = currentUser.id;

                await axios.post('/api/posts', this.form);

                // Push to Home with a query indicating success
                this.$router.push({ path: '/', query: { published: 'true' } });

            } catch (error) {
                if(error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('Erreur lors de la publication.');
                }
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>
