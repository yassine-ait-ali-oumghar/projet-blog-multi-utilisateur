<template>
  <div class="w-full">
    <!-- Success Alert -->
    <div v-if="successMessage" class="m-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 flex items-center justify-center gap-3">
        <span class="text-green-500 text-xl">✅</span>
        <span class="text-sm font-bold text-green-800 dark:text-green-200">{{ successMessage }}</span>
    </div>

    <!-- Header / "For You" Tabs -->
    <div class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200/60 dark:border-slate-800/60">
      <div class="flex items-center px-4 h-14">
        <h1 class="text-xl font-black text-slate-900 dark:text-white">{{ isExplorePage ? 'Top likes' : 'Home' }}</h1>
      </div>
      <div v-if="!isExplorePage" class="flex">
        <button @click="setFeed('for_you')" class="flex-1 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors py-4 font-bold relative" :class="activeFeed === 'for_you' ? 'text-slate-900 dark:text-white' : 'text-slate-500'">
          For you
          <div v-if="activeFeed === 'for_you'" class="absolute bottom-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-primary-500 rounded-full"></div>
        </button>
        <button @click="setFeed('following')" class="flex-1 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors py-4 font-bold relative" :class="activeFeed === 'following' ? 'text-slate-900 dark:text-white' : 'text-slate-500'">
          Following
          <div v-if="activeFeed === 'following'" class="absolute bottom-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-primary-500 rounded-full"></div>
        </button>
      </div>
    </div>

    <!-- Create Post Input Area (Twitter Style) -->
    <div v-if="currentUser" class="p-4 flex gap-4 border-b border-slate-200/60 dark:border-slate-800/60 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer" @click="$router.push('/write')">
      <div class="w-10 h-10 rounded-full text-white flex items-center justify-center font-bold shadow-sm shrink-0" :class="getColorHash(currentUser.name)">
         {{ getInitials(currentUser.name) }}
      </div>
      <div class="flex-1 pt-2">
        <div class="text-xl text-slate-500 dark:text-slate-400 mb-4">Quoi de neuf aujourd'hui ?</div>
        <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-3">
          <div class="flex gap-2 text-primary-500">
            <button class="w-8 h-8 rounded-full hover:bg-primary-50 dark:hover:bg-primary-900/20 flex items-center justify-center transition-colors">📸</button>
            <button class="w-8 h-8 rounded-full hover:bg-primary-50 dark:hover:bg-primary-900/20 flex items-center justify-center transition-colors">📊</button>
            <button class="w-8 h-8 rounded-full hover:bg-primary-50 dark:hover:bg-primary-900/20 flex items-center justify-center transition-colors">😊</button>
          </div>
          <button class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-6 rounded-full transition-colors opacity-60">Post</button>
        </div>
      </div>
    </div>

    <div v-if="isLoading" class="flex justify-center p-12">
      <div class="w-10 h-10 border-4 border-primary-100 dark:border-primary-900 border-t-primary-500 rounded-full animate-spin"></div>
    </div>

    <!-- Posts Feed -->
    <div v-else class="flex flex-col">
      <div 
        v-for="post in filteredPosts" 
        :key="post.is_repost ? `repost-${post.id}-${post.reposted_by.id}` : `post-${post.id}`"
        class="border-b border-slate-200/60 dark:border-slate-800/60 hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors p-4 flex flex-col gap-1 group animate-slide-up"
      >
        <!-- Repost Label -->
        <div v-if="post.is_repost" class="flex items-center gap-2 ml-9 mb-1 text-[13px] font-bold text-slate-500 dark:text-slate-400">
            <span>🔁</span>
            <span>{{ post.reposted_by?.id === currentUser?.id ? 'You' : post.reposted_by?.name }} reposted</span>
        </div>

        <div class="flex gap-3">
            <!-- Avatar -->
            <div class="w-10 h-10 rounded-full text-white flex items-center justify-center font-bold shadow-sm shrink-0 mt-1 cursor-pointer hover:opacity-80 transition-opacity" :class="getColorHash(post.user?.name)">
              {{ getInitials(post.user?.name) }}
            </div>

            <!-- Post Content -->
            <div class="flex-1 min-w-0">
          <!-- Header (Name, Username, Time) -->
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5 text-[15px] truncate">
              <span class="font-bold text-slate-900 dark:text-white hover:underline cursor-pointer truncate">{{ post.user?.name || 'Anonymous' }}</span>
              <button
                v-if="currentUser && currentUser.id !== post.user_id"
                @click="toggleFollow(post)"
                class="ml-1 text-xs font-bold px-2 py-0.5 rounded-full border transition-colors"
                :class="post.is_following_author ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 border-primary-200 dark:border-primary-800'"
              >
                {{ post.is_following_author ? '✅ Following' : '➕ Follow' }}
              </button>
              <span class="text-slate-500 truncate hidden sm:inline">@{{ formatUsername(post.user?.name) }}</span>
              <span class="text-slate-500">·</span>
              <span class="text-slate-500 hover:underline cursor-pointer">{{ formatShortDate(post.created_at) }}</span>
            </div>

            <!-- Owner Actions Dropdown -->
            <div v-if="currentUser && currentUser.id === post.user_id" class="relative group/menu">
              <button class="w-8 h-8 rounded-full hover:bg-primary-50 dark:hover:bg-primary-900/20 text-slate-400 hover:text-primary-500 flex items-center justify-center transition-colors">
                •••
              </button>
              <div class="absolute right-0 top-8 bg-white dark:bg-slate-900 shadow-xl rounded-xl border border-slate-100 dark:border-slate-800 w-32 hidden group-hover/menu:block z-20 py-1">
                <button @click="openEditModal(post)" class="w-full text-left px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center gap-2">
                  ✏️ Edit
                </button>
                <button @click="confirmDelete(post)" class="w-full text-left px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2">
                  🗑️ Delete
                </button>
              </div>
            </div>
          </div>

          <!-- Body -->
          <div class="mt-1">
            <h2 class="text-[15px] font-bold text-slate-900 dark:text-white mb-1">{{ post.title }}</h2>
            <p class="text-[15px] text-slate-800 dark:text-slate-300 leading-normal whitespace-pre-wrap break-all overflow-hidden">
              <template v-if="post.description && post.description.length > 200">
                <span v-if="!post.showFullText">
                  {{ post.description.substring(0, 200) }}...
                  <button @click.stop="post.showFullText = true" class="text-primary-500 hover:underline font-bold ml-1">Show more</button>
                </span>
                <span v-else>
                  {{ post.description }}
                  <button @click.stop="post.showFullText = false" class="text-primary-500 hover:underline font-bold ml-1">Show less</button>
                </span>
              </template>
              <template v-else>
                {{ post.description }}
              </template>
            </p>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-between mt-3 text-slate-500 dark:text-slate-400 max-w-md pr-4">
            <button @click="toggleComments(post)" class="flex items-center gap-2 group/btn hover:text-blue-500 transition-colors">
              <div class="w-8 h-8 rounded-full group-hover/btn:bg-blue-50 dark:group-hover/btn:bg-blue-900/20 flex items-center justify-center transition-colors">
                <span class="text-lg">💬</span>
              </div>
              <span class="text-sm font-medium">{{ post.comments_count || 0 }}</span>
            </button>

            <button
              @click="toggleRepost(post)"
              class="flex items-center gap-2 group/btn hover:text-green-500 transition-colors"
              :class="{ 'text-green-600 dark:text-green-400': post.reposted_by_current_user }"
            >
              <div class="w-8 h-8 rounded-full group-hover/btn:bg-green-50 dark:group-hover/btn:bg-green-900/20 flex items-center justify-center transition-colors">
                <span class="text-lg">🔁</span>
              </div>
              <span class="text-sm font-medium">{{ post.reposts_count || 0 }}</span>
            </button>

            <button
              @click="toggleLike(post)"
              class="flex items-center gap-2 group/btn hover:text-pink-500 transition-colors"
              :class="{ 'text-pink-600 dark:text-pink-400': post.liked_by_current_user }"
            >
              <div class="w-8 h-8 rounded-full group-hover/btn:bg-pink-50 dark:group-hover/btn:bg-pink-900/20 flex items-center justify-center transition-colors">
                <span class="text-lg">❤️</span>
              </div>
              <span class="text-sm font-medium">{{ post.likes_count || 0 }}</span>
            </button>

            <button class="flex items-center gap-2 group/btn hover:text-primary-500 transition-colors">
              <div class="w-8 h-8 rounded-full group-hover/btn:bg-primary-50 dark:group-hover/btn:bg-primary-900/20 flex items-center justify-center transition-colors">
                <span class="text-lg">📊</span>
              </div>
            </button>
          </div>

          <!-- Comments Drawer (Inline) -->
          <transition name="slide-fade">
              <div v-if="post.showComments" class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                  <!-- Comments List -->
                  <div v-if="post.comments && post.comments.length > 0" class="space-y-4 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                      <div v-for="comment in post.comments" :key="comment.id" class="flex gap-3 group/comment">
                          <div class="w-8 h-8 rounded-full text-white flex items-center justify-center font-bold text-xs shrink-0" :class="getColorHash(comment.user?.name)">
                              {{ getInitials(comment.user?.name) }}
                          </div>
                          <div class="flex-1">
                              <div class="flex items-center gap-1.5 text-sm">
                                  <span class="font-bold text-slate-900 dark:text-white">{{ comment.user?.name || 'Anonymous' }}</span>
                                  <span class="text-slate-500">@{{ formatUsername(comment.user?.name) }}</span>
                                  <span class="text-slate-500">· {{ formatShortDate(comment.created_at) }}</span>
                                  
                                  <button 
                                      v-if="currentUser && currentUser.id === comment.user_id"
                                      @click="deleteComment(post, comment)"
                                      class="ml-auto text-slate-400 hover:text-red-500 opacity-0 group-hover/comment:opacity-100 transition-all px-2"
                                      title="Delete reply"
                                  >
                                      🗑️
                                  </button>
                              </div>
                              <p class="text-[15px] text-slate-800 dark:text-slate-300 mt-0.5 leading-normal">
                                  {{ comment.content }}
                              </p>
                          </div>
                      </div>
                  </div>

                  <div v-else-if="!post.commentsLoading" class="py-4 text-center">
                      <p class="text-[15px] text-slate-500 font-medium">No replies yet.</p>
                  </div>

                  <div v-if="post.commentsLoading" class="flex justify-center p-4">
                      <div class="w-6 h-6 border-2 border-primary-100 dark:border-primary-900 border-t-primary-500 rounded-full animate-spin"></div>
                  </div>

                  <!-- Create Comment Form -->
                  <div class="mt-4 flex gap-3 items-start">
                      <div v-if="currentUser" class="w-8 h-8 rounded-full text-white flex items-center justify-center font-bold text-xs shrink-0" :class="getColorHash(currentUser.name)">
                          {{ getInitials(currentUser.name) }}
                      </div>
                      <div v-else class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs shrink-0">👤</div>
                      
                      <div class="flex-1" v-if="currentUser">
                          <textarea 
                              v-model="post.newComment" 
                              rows="1" 
                              class="w-full bg-transparent border-none text-[15px] text-slate-900 dark:text-white placeholder-slate-500 focus:ring-0 p-1.5 resize-none outline-none" 
                              placeholder="Post your reply..."
                              @input="resizeTextarea($event)"
                          ></textarea>
                          <div class="flex justify-end mt-2 border-t border-slate-100 dark:border-slate-800 pt-2">
                              <button 
                                  @click="submitComment(post)" 
                                  :disabled="!post.newComment || post.isSubmitting"
                                  class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-1.5 px-4 rounded-full transition-colors disabled:opacity-50"
                              >
                                  <span v-if="post.isSubmitting">Replying...</span>
                                  <span v-else>Reply</span>
                              </button>
                          </div>
                      </div>
                      <div v-else class="flex-1 pt-1">
                          <p class="text-[15px] text-slate-500">
                              <router-link to="/login" class="text-primary-500 hover:underline font-bold">Log in</router-link> to reply.
                          </p>
                      </div>
                  </div>
              </div>
          </transition>
        </div>
      </div>
    </div>
    </div>

    <!-- No Results Found -->
    <div v-if="!isLoading && filteredPosts.length === 0 && $route.query.search" class="p-12 text-center">
      <div class="text-4xl mb-4">🔍</div>
      <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">No results for "{{ $route.query.search }}"</h3>
      <p class="text-slate-500 dark:text-slate-400">Try searching for something else, or check for typos.</p>
    </div>

    <!-- Empty State -->
    <div v-if="!isLoading && posts.length === 0 && !$route.query.search" class="p-12 text-center max-w-sm mx-auto">
       <div class="text-6xl mb-6">📭</div>
       <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-2">Nothing to see here</h3>
       <p class="text-slate-500 dark:text-slate-400 text-[15px] mb-8">There are no posts yet. Be the first to start the conversation!</p>
       <div class="mt-8 flex justify-center">
         <button @click="$router.push('/write')" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-3 px-8 rounded-full shadow-lg shadow-primary-500/20 transition-all active:scale-95">
           Post an article
         </button>
       </div>
    </div>

    <!-- Edit Modal Overlay -->
    <div v-if="editModal.visible" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="closeEditModal">
      <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
      <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-[600px] z-10 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800">
          <div class="flex items-center gap-6">
            <button @click="closeEditModal" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-lg">✕</button>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Edit post</h2>
          </div>
          <button @click="saveEdit" class="bg-slate-900 dark:bg-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 text-white font-bold py-1.5 px-4 rounded-full transition-colors">
            Save
          </button>
        </div>

        <div class="p-4 overflow-y-auto flex gap-4">
          <div class="w-10 h-10 rounded-full text-white flex items-center justify-center font-bold shrink-0" :class="getColorHash(currentUser?.name)">
             {{ getInitials(currentUser?.name) }}
          </div>
          <div class="flex-1 space-y-4 pt-1">
            <input 
              v-model="editModal.form.title" 
              type="text" 
              class="input-modern bg-transparent border-none text-xl font-bold p-0 focus:ring-0 text-slate-900 dark:text-white"
              placeholder="Post title"
            >
            <span class="text-xs text-red-500 mt-1 block" v-if="editModal.errors.title">{{ editModal.errors.title[0] }}</span>
            
            <textarea 
              v-model="editModal.form.description" 
              rows="6" 
              class="input-modern bg-transparent border-none text-[15px] p-0 focus:ring-0 resize-y text-slate-800 dark:text-slate-300"
              placeholder="What's happening?"
            ></textarea>
            <span class="text-xs text-red-500 mt-1 block" v-if="editModal.errors.description">{{ editModal.errors.description[0] }}</span>
          </div>
        </div>
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
            activeFeed: 'for_you',
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
        },
        isExplorePage() {
            return this.$route.name === 'explore';
        },
        filteredPosts() {
            const query = (this.$route.query.search || '').toLowerCase();
            if (!query) return this.posts;
            
            return this.posts.filter(post => {
                const titleMatch = post.title.toLowerCase().includes(query);
                const userNameMatch = post.user && post.user.name.toLowerCase().includes(query);
                return titleMatch || userNameMatch;
            });
        }
    },
    watch: {
        '$route.name'() {
            if (!this.isExplorePage) {
                this.activeFeed = this.$route.query.feed === 'following' ? 'following' : 'for_you';
            }
            this.fetchPosts();
        },
        '$route.query.feed'() {
            if (!this.isExplorePage) {
                this.activeFeed = this.$route.query.feed === 'following' ? 'following' : 'for_you';
                this.fetchPosts();
            }
        },
    },
    mounted() {
        if (!this.isExplorePage) {
            this.activeFeed = this.$route.query.feed === 'following' ? 'following' : 'for_you';
        }
        this.fetchPosts();
        if (this.$route.query.published) {
            this.successMessage = 'Post published successfully!';
            setTimeout(() => { this.successMessage = ''; }, 5000);
        }
    },
    methods: {
        resizeTextarea(e) {
            e.target.style.height = 'auto';
            e.target.style.height = (e.target.scrollHeight) + 'px';
        },
        async fetchPosts() {
            try {
                const response = await axios.get('/api/public-posts', {
                    params: this.isExplorePage
                        ? { sort_by_likes: 1 }
                        : (this.activeFeed === 'following' ? { following: 1 } : {})
                });
                // Initialize local UI state for each post
                this.posts = response.data.map(post => ({
                    ...post,
                    showComments: false,
                    showFullText: false,
                    comments: [],
                    commentsLoading: false,
                    newComment: '',
                    isSubmitting: false,
                    likes_count: post.likes_count || 0,
                    reposts_count: post.reposts_count || 0,
                    liked_by_current_user: !!post.liked_by_current_user,
                    reposted_by_current_user: !!post.reposted_by_current_user,
                    is_following_author: !!post.is_following_author
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
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMins / 60);
            const diffDays = Math.floor(diffHours / 24);

            if (diffMins < 60) return `${diffMins}m`;
            if (diffHours < 24) return `${diffHours}h`;
            if (diffDays < 7) return `${diffDays}d`;

            return new Intl.DateTimeFormat('en-US', {
                month: 'short', day: 'numeric'
            }).format(date);
        },
        formatUsername(name) {
            if(!name) return 'user';
            return name.replace(/\s+/g, '').toLowerCase();
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
            if (!name) return 'bg-slate-500';
            const colors = [
                'bg-blue-500', 
                'bg-emerald-500', 
                'bg-purple-500', 
                'bg-rose-500', 
                'bg-amber-500'
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
        async toggleLike(post) {
            if (!this.currentUser) {
                this.$router.push('/login');
                return;
            }

            try {
                const response = await axios.post(`/api/posts/${post.id}/like`);
                post.liked_by_current_user = response.data.liked;
                post.likes_count = response.data.likes_count;
            } catch (error) {
                console.error('Failed to toggle like', error);
            }
        },
        async toggleRepost(post) {
            if (!this.currentUser) {
                this.$router.push('/login');
                return;
            }

            try {
                const response = await axios.post(`/api/posts/${post.id}/repost`);
                post.reposted_by_current_user = response.data.reposted;
                post.reposts_count = response.data.reposts_count;
                
                // If we just reposted, refresh to show the new item in feed
                if (post.reposted_by_current_user) {
                    this.fetchPosts();
                }
            } catch (error) {
                console.error('Failed to toggle repost', error);
            }
        },
        async toggleFollow(post) {
            if (!this.currentUser) {
                this.$router.push('/login');
                return;
            }
            try {
                const response = await axios.post(`/api/users/${post.user_id}/follow`);
                const following = response.data.following;
                this.posts = this.posts
                    .map(item => item.user_id === post.user_id ? { ...item, is_following_author: following } : item)
                    .filter(item => this.activeFeed !== 'following' || item.is_following_author);
            } catch (error) {
                console.error('Failed to toggle follow', error);
            }
        },
        setFeed(feed) {
            if (this.isExplorePage || this.activeFeed === feed) return;
            this.activeFeed = feed;
            this.$router.replace({ path: '/', query: feed === 'following' ? { feed: 'following' } : {} });
            this.fetchPosts();
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
                this.successMessage = 'Post updated successfully!';
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
            if (!confirm(`Delete post "${post.title}"? This cannot be undone.`)) return;
            try {
                await axios.delete(`/api/posts/${post.id}`);
                this.posts = this.posts.filter(p => p.id !== post.id);
                this.successMessage = 'Post deleted successfully!';
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
  transform: translateY(-10px);
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
