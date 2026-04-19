<template>
  <div>
    <!-- Success Alert -->
    <div v-if="successMessage" class="mb-8 max-w-2xl mx-auto bg-green-50 border border-green-200 rounded-xl p-4 flex items-center justify-center gap-3">
        <span class="text-green-500 text-xl">✅</span>
        <span class="text-sm font-bold text-green-800">{{ successMessage }}</span>
    </div>

    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-b from-[#f8faff] to-[#eef2ff] rounded-3xl border border-slate-200/60 mb-12 shadow-sm">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-100/50 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-purple-100/50 rounded-full blur-3xl opacity-50"></div>
        
        <div class="relative text-center py-16 px-6 z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-[#2563eb] text-xs font-bold uppercase tracking-wider mb-6 border border-blue-200">
                <span class="animate-bounce">📝</span> Blog & Articles
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-[#0f172a] tracking-tight mb-6">
                Welcome to <span class="text-gradient">Our Blog</span>
            </h1>
            <p class="text-[#64748b] text-lg md:text-xl max-w-2xl mx-auto leading-relaxed mb-8">
                Discover the latest stories, insights, and thoughts from our brilliant authors around the world.
            </p>

        </div>
    </div>

    <div v-if="isLoading" class="flex justify-center p-12">
      <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
    </div>

    <!-- Posts Grid -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <div 
        v-for="post in posts" 
        :key="post.id"
        class="bg-white rounded-[16px] shadow-sm border border-slate-200 hover:shadow-[0_12px_40px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group"
      >
        <!-- Article Header & Body -->
        <div class="p-6 flex-1 flex flex-col">
          <div class="flex items-center gap-2 mb-4">
             <div class="relative w-2 h-2 rounded-full bg-[#2563eb] animate-pulse-blue"></div>
             <span class="text-[10px] font-bold text-[#2563eb] uppercase tracking-widest">ARTICLE</span>
          </div>
          <h2 class="text-lg font-bold text-[#0f172a] mb-3 group-hover:text-[#2563eb] transition-colors line-clamp-2 leading-snug break-words">
            {{ post.title }}
          </h2>
          <div class="relative">
            <p 
              class="text-[#64748b] text-sm leading-relaxed mb-4 transition-all duration-300 break-all whitespace-pre-wrap"
              :class="{ 'line-clamp-2 overflow-hidden': !post.isExpanded }"
              :style="!post.isExpanded ? 'display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2;' : ''"
            >
              {{ post.description }}
            </p>
            <button 
              v-if="post.description && post.description.length > 50"
              @click="post.isExpanded = !post.isExpanded" 
              class="text-[#2563eb] text-[11px] font-black hover:underline mb-5 block"
            >
              {{ post.isExpanded ? 'Voir moins ↑' : '... Voir plus' }}
            </button>
          </div>

          <div class="flex items-center justify-between mb-4">
              <span class="text-[10px] text-slate-400 font-bold tracking-wider uppercase">— {{ formatShortDate(post.created_at) }}</span>
              <button @click="toggleComments(post)" class="text-[#2563eb] text-xs font-bold hover:underline">
                  {{ post.showComments ? 'Masquer' : 'Commentaires →' }}
              </button>
          </div>

          <div class="h-px bg-slate-100 mb-4 w-full"></div>

          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
               <div class="w-8 h-8 rounded-full bg-[#f1f5f9] border border-slate-100 text-[#0f172a] flex justify-center items-center text-xs font-bold shadow-sm" :class="getColorHash(post.user?.name)">
                 {{ getInitials(post.user?.name) }}
               </div>
               <span class="text-xs font-bold text-slate-700">{{ post.user?.name || 'Anonymous' }}</span>
            </div>
            <div class="bg-[#f1f5f9] px-3 py-1.5 rounded-xl border border-slate-100 flex items-center gap-1.5 shadow-sm">
                <span class="text-[10px]">💬</span>
                <span class="text-[10px] font-bold text-[#0f172a]">{{ post.comments_count }}</span>
            </div>
          </div>

          <!-- Owner Actions (Post Edit/Delete) -->
          <div v-if="currentUser && currentUser.id === post.user_id" class="mt-5 flex items-center gap-2">
            <button @click="openEditModal(post)" class="flex-1 flex items-center justify-center gap-1.5 text-[10px] font-bold text-[#2563eb] bg-blue-50 border border-blue-100 rounded-xl py-2 hover:bg-blue-100 transition-colors">
              <span>✏️</span> Modifier
            </button>
            <button @click="confirmDelete(post)" class="flex-1 flex items-center justify-center gap-1.5 text-[10px] font-bold text-red-600 bg-red-50 border border-red-100 rounded-xl py-2 hover:bg-red-100 transition-colors">
              <span>🗑️</span> Supprimer
            </button>
          </div>
        </div>

        <!-- Comments Drawer -->
        <transition name="slide-fade">
            <div v-if="post.showComments" class="bg-slate-50 border-t border-slate-100 p-6 space-y-4 shadow-inner">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2 mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    Commentaires
                </h4>

                <!-- Comments List -->
                <div v-if="post.comments && post.comments.length > 0" class="space-y-4 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                    <div v-for="comment in post.comments" :key="comment.id" class="flex gap-3 items-start group">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0 shadow-sm border border-indigo-200">
                            {{ getInitials(comment.user?.name) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-0.5">
                                <span class="text-xs font-bold text-slate-700">{{ comment.user?.name }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-slate-400">{{ formatShortDate(comment.created_at) }}</span>
                                    <button 
                                        v-if="currentUser && currentUser.id === comment.user_id"
                                        @click="deleteComment(post, comment)"
                                        class="text-red-400 hover:text-red-600 transition-colors opacity-0 group-hover:opacity-100"
                                        title="Supprimer mon commentaire"
                                    >
                                        🗑️
                                    </button>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed bg-white p-3 rounded-xl border border-slate-100 shadow-sm inline-block break-all whitespace-pre-wrap">
                                {{ comment.content }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else-if="!post.commentsLoading" class="py-4 text-center">
                    <p class="text-sm text-slate-400 italic">Aucun commentaire pour l'instant. Soyez le premier !</p>
                </div>

                <!-- Loading State -->
                <div v-if="post.commentsLoading" class="flex justify-center p-4">
                    <div class="w-5 h-5 border-2 border-slate-200 border-t-indigo-500 rounded-full animate-spin"></div>
                </div>

                <!-- Create Comment Form -->
                <div class="pt-4 border-t border-slate-200">
                    <div v-if="currentUser">
                        <textarea 
                            v-model="post.newComment" 
                            rows="2" 
                            class="w-full bg-white border border-slate-200 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-200 p-3 transition-colors placeholder-slate-400 resize-none shadow-sm mb-2" 
                            placeholder="Écrire un commentaire..."
                        ></textarea>
                        <div class="flex justify-end">
                            <button 
                                @click="submitComment(post)" 
                                :disabled="!post.newComment || post.isSubmitting"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow-sm transition-all disabled:opacity-50"
                            >
                                <span v-if="post.isSubmitting">Publication...</span>
                                <span v-else>Publier</span>
                            </button>
                        </div>
                    </div>
                    <div v-else class="text-center py-2">
                        <p class="text-xs text-slate-500">
                            <router-link to="/login" class="text-indigo-600 font-bold hover:underline">Connectez-vous</router-link> pour laisser un commentaire.
                        </p>
                    </div>
                </div>
            </div>
        </transition>
      </div>
    </div>

    <div v-if="!isLoading && posts.length === 0" class="text-center py-24 bg-white rounded-[32px] border border-slate-100 shadow-sm">
       <div class="relative inline-block mb-6">
           <span class="text-7xl block animate-bounce">📭</span>
           <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-blue-600 rounded-full border-4 border-white flex items-center justify-center text-white text-[10px]">!</div>
       </div>
       <h3 class="text-2xl font-black text-[#0f172a]">Aucun article pour l'instant</h3>
       <p class="text-[#64748b] mt-3 font-medium max-w-sm mx-auto">Soyez le premier à publier et commencez la discussion !</p>
       <div class="mt-8">
           <router-link to="/write" class="bg-[#2563eb] text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-blue-200 hover:scale-105 transition-transform inline-block">
               ✍️ Publier un article
           </router-link>
       </div>
    </div>

    <!-- Edit Modal Overlay (Rest of Article logic stays same) -->
    <div v-if="editModal.visible" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="closeEditModal">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
      <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg p-8 z-10">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">✏️ Modifier l'article</h2>
          <button @click="closeEditModal" class="text-slate-400 hover:text-slate-700 text-xl transition-colors">&times;</button>
        </div>

        <form @submit.prevent="saveEdit" class="space-y-5">
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-1.5">Titre</label>
            <input 
              v-model="editModal.form.title" 
              type="text" 
              class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 transition-colors"
            >
            <span class="text-xs text-red-500 mt-1 block" v-if="editModal.errors.title">{{ editModal.errors.title[0] }}</span>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-1.5">Description</label>
            <textarea 
              v-model="editModal.form.description" 
              rows="6" 
              class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 transition-colors resize-y"
            ></textarea>
            <span class="text-xs text-red-500 mt-1 block" v-if="editModal.errors.description">{{ editModal.errors.description[0] }}</span>
          </div>
          <div class="flex justify-end gap-3 pt-2">
            <button type="button" @click="closeEditModal" class="px-5 py-2.5 text-sm font-medium text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">Annuler</button>
            <button type="submit" :disabled="editModal.isSaving" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-colors disabled:opacity-50">
              <span v-if="editModal.isSaving">Sauvegarde...</span>
              <span v-else>Sauvegarder</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Home',
    data() {
        return {
            posts: [],
            isLoading: true,
            successMessage: '',
            editModal: {
                visible: false,
                postId: null,
                form: { title: '', description: '' },
                errors: {},
                isSaving: false
            }
        }
    },
    computed: {
        currentUser() {
            const userStr = localStorage.getItem('user');
            return userStr ? JSON.parse(userStr) : null;
        }
    },
    mounted() {
        this.fetchPosts();
        if (this.$route.query.published) {
            this.successMessage = 'Article publié avec succès ! Il est désormais visible par tous.';
            setTimeout(() => { this.successMessage = ''; }, 5000);
        }
    },
    methods: {
        async fetchPosts() {
            try {
                const response = await axios.get('/api/public-posts');
                // Initialize local UI state for each post
                this.posts = response.data.map(post => ({
                    ...post,
                    showComments: false,
                    isExpanded: false,
                    comments: [],
                    commentsLoading: false,
                    newComment: '',
                    isSubmitting: false
                }));
            } catch (error) {
                console.error("Failed to load posts", error);
            } finally {
                this.isLoading = false;
            }
        },
        formatShortDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('en-US', {
                month: 'short', day: 'numeric', year: 'numeric'
            }).format(date);
        },
        getInitials(name) {
            if (!name) return '?';
            return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        },

        // --- Comments Logic ---
        async toggleComments(post) {
            post.showComments = !post.showComments;
            if (post.showComments && post.comments.length === 0) {
                this.fetchComments(post);
            }
        },
        async fetchComments(post) {
            post.commentsLoading = true;
            try {
                const response = await axios.get(`/api/posts/${post.id}/comments`);
                post.comments = response.data;
            } catch (error) {
                console.error("Failed to fetch comments", error);
            } finally {
                post.commentsLoading = false;
            }
        },
        async submitComment(post) {
            if (!post.newComment) return;
            post.isSubmitting = true;
            try {
                const response = await axios.post(`/api/posts/${post.id}/comments`, {
                    content: post.newComment
                });
                post.comments.unshift(response.data);
                post.newComment = '';
                post.comments_count++;
            } catch (error) {
                console.error("Failed to submit comment", error);
            } finally {
                post.isSubmitting = false;
            }
        },
        // --- Actions ---
        getColorHash(name) {
            if (!name) return 'bg-slate-500 text-white';
            const colors = [
                'bg-blue-100 text-blue-600', 
                'bg-emerald-100 text-emerald-600', 
                'bg-purple-100 text-purple-600', 
                'bg-rose-100 text-rose-600', 
                'bg-amber-100 text-amber-600'
            ];
            const sum = name.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0);
            return colors[sum % colors.length];
        },
        async deleteComment(post, comment) {
            if (!confirm('Voulez-vous vraiment supprimer ce commentaire ?')) return;
            try {
                await axios.delete(`/api/posts/${post.id}/comments/${comment.id}`);
                post.comments = post.comments.filter(c => c.id !== comment.id);
                post.comments_count--;
            } catch (error) {
                console.error("Failed to delete comment", error);
            }
        },

        // --- Edit Modal ---
        openEditModal(post) {
            this.editModal.postId = post.id;
            this.editModal.form.title = post.title;
            this.editModal.form.description = post.description;
            this.editModal.errors = {};
            this.editModal.visible = true;
        },
        closeEditModal() {
            this.editModal.visible = false;
        },
        async saveEdit() {
            this.editModal.isSaving = true;
            this.editModal.errors = {};
            try {
                const response = await axios.put(`/api/posts/${this.editModal.postId}`, this.editModal.form);
                const idx = this.posts.findIndex(p => p.id === this.editModal.postId);
                if (idx !== -1) {
                    // Retain UI state (comments, etc)
                    const oldPost = this.posts[idx];
                    this.posts[idx] = { ...oldPost, ...response.data };
                }
                this.closeEditModal();
                this.successMessage = 'Article modifié avec succès !';
                setTimeout(() => { this.successMessage = ''; }, 4000);
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.editModal.errors = error.response.data.errors;
                }
            } finally {
                this.editModal.isSaving = false;
            }
        },

        // --- Delete ---
        async confirmDelete(post) {
            if (!confirm(`Êtes-vous sûr de vouloir supprimer l'article "${post.title}" ?`)) return;
            try {
                await axios.delete(`/api/posts/${post.id}`);
                this.posts = this.posts.filter(p => p.id !== post.id);
                this.successMessage = 'Article supprimé avec succès !';
                setTimeout(() => { this.successMessage = ''; }, 4000);
            } catch (error) {
                console.error("Failed to delete post", error);
            }
        }
    }
}
</script>

<style scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}
.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #e2e8f0;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #cbd5e1;
}
</style>
