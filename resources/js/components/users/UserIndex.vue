<template>
  <div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">Users Management</h1>
        <p class="text-sm text-slate-500 mt-1">
          Showing <span class="font-semibold text-blue-600">{{ filteredUsers.length }}</span> registered users
        </p>
      </div>

      <div class="flex items-center gap-4 w-full sm:w-auto">
        <!-- Search Bar -->
        <div class="relative w-full sm:w-64">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            🔍
          </span>
          <input 
            v-model="searchQuery" 
            type="text" 
            class="w-full bg-white border border-slate-300 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow shadow-sm"
            placeholder="Search name or email..."
          >
        </div>

        <router-link :to="{ name: 'users.create' }" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors flex items-center gap-2 whitespace-nowrap">
          <span>➕</span> Add User
        </router-link>
      </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider border-b border-slate-200">
              <th class="px-6 py-4 font-semibold"># ID</th>
              <th class="px-6 py-4 font-semibold">Avatar</th>
              <th class="px-6 py-4 font-semibold">Name</th>
              <th class="px-6 py-4 font-semibold">Email</th>
              <th class="px-6 py-4 font-semibold">Posts</th>
              <th class="px-6 py-4 font-semibold">Joined At</th>
              <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
            <tr 
              v-for="(user, index) in filteredUsers" 
              :key="user.id" 
              class="hover:bg-slate-50 transition-colors"
              :class="{ 'bg-slate-50/50': index % 2 !== 0 }"
            >
              <td class="px-6 py-4 font-medium text-slate-500">#{{ user.id }}</td>
              <td class="px-6 py-4">
                <div class="w-8 h-8 rounded-full text-white flex items-center justify-center font-bold shadow-sm" :class="getColorHash(user.name)">
                  {{ getInitials(user.name) }}
                </div>
              </td>
              <td class="px-6 py-4 font-medium text-slate-800">{{ user.name }}</td>
              <td class="px-6 py-4 text-slate-500">{{ user.email }}</td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                  {{ user.posts_count || 0 }} posts
                </span>
              </td>
              <td class="px-6 py-4 text-slate-500">{{ formatDate(user.created_at) }}</td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <router-link :to="{ name: 'users.show', params: { id: user.id } }" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="View details">
                    👁
                  </router-link>
                  <router-link :to="{ name: 'users.edit', params: { id: user.id } }" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-md transition-colors" title="Edit user">
                    ✏️
                  </router-link>
                  <button @click="deleteUser(user.id)" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Delete user">
                    🗑
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredUsers.length === 0">
              <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                <div class="text-3xl mb-2">📭</div>
                <p>No users found matching your criteria.</p>
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
