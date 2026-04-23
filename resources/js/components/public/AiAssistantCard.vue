<template>
  <div class="mt-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden">
    <div class="p-4 border-b border-slate-100 dark:border-slate-800">
      <h2 class="font-black text-xl text-slate-800 dark:text-white">Nexus Intelligence</h2>
      <p class="text-sm text-primary-500 font-bold mt-1">Chatbot IA</p>
    </div>

    <div class="p-4 space-y-3">
      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-3 min-h-[110px] text-sm text-slate-700 dark:text-slate-300 whitespace-pre-wrap transition-colors">
        {{ answer }}
      </div>

      <textarea
        v-model="question"
        rows="3"
        class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 p-3 text-sm transition-colors resize-none"
        placeholder="Posez n'importe quelle question..."
      ></textarea>

      <button
        @click="askAssistant"
        :disabled="isLoading || !question.trim()"
        class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-full transition-colors disabled:opacity-60 shadow-md shadow-primary-500/10"
      >
        {{ isLoading ? 'Thinking...' : 'Ask AI' }}
      </button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'AiAssistantCard',
    data() {
        return {
            question: '',
            answer: 'Bonjour, je suis votre assistant AI. Posez une question et je vous reponds.',
            isLoading: false
        };
    },
    methods: {
        async askAssistant() {
            if (!this.question.trim()) return;

            this.isLoading = true;
            try {
                const response = await axios.post('/api/assistant', {
                    question: this.question
                });
                this.answer = response.data.answer || 'Je n ai pas pu generer de reponse.';
                this.question = '';
            } catch (error) {
                this.answer = 'Erreur de reponse AI. Veuillez reessayer.';
            } finally {
                this.isLoading = false;
            }
        }
    }
};
</script>
