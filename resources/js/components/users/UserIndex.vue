<template>
  <div class="max-w-6xl mx-auto animate-slide-up">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-gradient-to-br from-primary-600 to-primary-500 text-white rounded-2xl p-5 shadow-lg shadow-primary-500/20">
        <p class="text-sm text-primary-100">Total Users</p>
        <p class="text-3xl font-black mt-1">{{ users.length }}</p>
      </div>
      <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-sm">
        <p class="text-sm text-slate-500">With Posts</p>
        <p class="text-3xl font-black text-slate-800 mt-1">{{ usersWithPosts }}</p>
      </div>
      <div class="bg-white rounded-2xl p-5 border border-slate-200/70 shadow-sm">
        <p class="text-sm text-slate-500">No Posts Yet</p>
        <p class="text-3xl font-black text-slate-800 mt-1">{{ usersWithoutPosts }}</p>
      </div>
    </div>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60">
      <div>
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Users Management</h1>
        <p class="text-sm text-slate-500 mt-2">
          Showing <span class="font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded-md">{{ filteredUsers.length }}</span> registered users
        </p>
      </div>

      <div class="flex items-center gap-4 w-full sm:w-auto">
        <!-- Search Bar -->
        <div class="relative w-full sm:w-72">
          <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
            🔍
          </span>
          <input 
            v-model="searchQuery" 
            type="text" 
            class="input-modern pl-11 py-2.5"
            placeholder="Search name or email..."
          >
        </div>

        <router-link :to="{ name: 'users.create' }" class="btn-primary py-2.5 px-5 flex items-center gap-2 whitespace-nowrap">
          <span class="text-lg">➕</span> 
          <span>Add User</span>
        </router-link>
      </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50/90 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-200/80">
              <th class="px-6 py-4"># ID</th>
              <th class="px-6 py-4">Avatar</th>
              <th class="px-6 py-4">Name</th>
              <th class="px-6 py-4">Email</th>
              <th class="px-6 py-4">Posts</th>
              <th class="px-6 py-4">Joined At</th>
              <th class="px-6 py-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
            <tr 
              v-for="(user, index) in filteredUsers" 
              :key="user.id" 
              class="hover:bg-primary-50/50 transition-colors group"
            >
              <td class="px-6 py-4 font-bold text-slate-400 group-hover:text-primary-500 transition-colors">#{{ user.id }}</td>
              <td class="px-6 py-4">
                <div class="w-9 h-9 rounded-xl text-white flex items-center justify-center font-bold shadow-sm" :class="getColorHash(user.name)">
                  {{ getInitials(user.name) }}
                </div>
              </td>
              <td class="px-6 py-4 font-bold text-slate-800 group-hover:text-primary-700 transition-colors">{{ user.name }}</td>
              <td class="px-6 py-4 text-slate-500 font-medium">{{ user.email }}</td>
              <td class="px-6 py-4">
                <span class="badge bg-blue-50 text-blue-700 border border-blue-200/60">
                  {{ user.posts_count || 0 }} posts
                </span>
              </td>
              <td class="px-6 py-4 text-slate-500 font-medium">{{ formatDate(user.created_at) }}</td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
                  <router-link :to="{ name: 'users.show', params: { id: user.id } }" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-100 rounded-xl transition-colors" title="View details">
                    View
                  </router-link>
                  <router-link :to="{ name: 'users.edit', params: { id: user.id } }" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-100 rounded-xl transition-colors" title="Edit user">
                    Edit
                  </router-link>
                  <button @click="deleteUser(user.id)" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Delete user">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredUsers.length === 0">
              <td colspan="7" class="px-6 py-16 text-center text-slate-500">
                <div class="text-4xl mb-4 animate-bounce">📭</div>
                <p class="text-lg font-bold text-slate-700">No users found matching your criteria.</p>
                <p class="text-sm text-slate-400 mt-1">Try adjusting your search terms.</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'UserIndex',
    data() {
        return {
            users: [],
            searchQuery: ''
        }
    },
    computed: {
        filteredUsers() {
            if (!this.searchQuery) return this.users;
            const query = this.searchQuery.toLowerCase();
            return this.users.filter(user => 
                user.name.toLowerCase().includes(query) || 
                user.email.toLowerCase().includes(query)
            );
        },
        usersWithPosts() {
            return this.users.filter(user => (user.posts_count || 0) > 0).length;
        },
        usersWithoutPosts() {
            return this.users.filter(user => (user.posts_count || 0) === 0).length;
        }
    },
    mounted() {
        this.fetchUsers();
    },
    methods: {
        async fetchUsers() {
            try {
                const response = await axios.get('/api/users');
                this.users = response.data;
            } catch (error) {
                console.error("Error fetching users:", error);
            }
        },
        async deleteUser(id) {
            if (confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) {
                try {
                    await axios.delete(`/api/users/${id}`, {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`
                        }
                    });
                    // Mise à jour locale de la liste
                    this.users = this.users.filter(u => u.id !== id);
                } catch (error) {
                    console.error("Erreur suppression:", error.response); // Voir l'erreur exacte
                    alert("Erreur : " + (error.response?.data?.message || "Impossible de supprimer cet utilisateur"));
                }
            }
        },
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric', month: 'short', day: 'numeric'
            }).format(date);
        },
        getInitials(name) {
            if (!name) return '?';
            return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        },
        getColorHash(name) {
            if (!name) return 'bg-slate-500';
            const colors = ['bg-blue-500', 'bg-emerald-500', 'bg-purple-500', 'bg-rose-500', 'bg-amber-500', 'bg-indigo-500'];
            const sum = name.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0);
            return colors[sum % colors.length];
        }
    }
}
</script>
