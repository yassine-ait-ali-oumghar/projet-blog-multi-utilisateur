import { createRouter, createWebHistory } from 'vue-router';
import PostIndex from './components/PostIndex.vue';
import PostCreate from './components/PostCreate.vue';
import PostEdit from './components/PostEdit.vue';
import PostShow from './components/PostShow.vue';

import UserIndex from './components/users/UserIndex.vue';
import UserCreate from './components/users/UserCreate.vue';
import UserEdit from './components/users/UserEdit.vue';
import UserShow from './components/users/UserShow.vue';

import Home from './components/public/Home.vue';
import PublicPostCreate from './components/public/PublicPostCreate.vue';
import Login from './components/auth/Login.vue';
import Register from './components/auth/Register.vue';

const routes = [
   
    { path: '/', component: Home, name: 'home', meta: { layout: 'PublicLayout' } },
    { path: '/write', component: PublicPostCreate, name: 'public.write', meta: { layout: 'PublicLayout', requiresAuth: true } },
    
   
    { path: '/login', component: Login, name: 'login', meta: { layout: 'AuthLayout', guestOnly: true } },
    { path: '/register', component: Register, name: 'register', meta: { layout: 'AuthLayout', guestOnly: true } },
    
    
    { path: '/admin', component: PostIndex, name: 'admin.dashboard', meta: { layout: 'AdminLayout', requiresAuth: true, requiresAdmin: true } },
    
    { path: '/admin/posts', component: PostIndex, name: 'posts.index', meta: { layout: 'AdminLayout', requiresAuth: true, requiresAdmin: true } },
    { path: '/admin/posts/create', component: PostCreate, name: 'posts.create', meta: { layout: 'AdminLayout', requiresAuth: true, requiresAdmin: true } },
    { path: '/admin/posts/:id/edit', component: PostEdit, name: 'posts.edit', meta: { layout: 'AdminLayout', requiresAuth: true, requiresAdmin: true } },
    { path: '/admin/posts/:id', component: PostShow, name: 'posts.show', meta: { layout: 'AdminLayout', requiresAuth: true, requiresAdmin: true } },
    
    { path: '/admin/users', component: UserIndex, name: 'users.index', meta: { layout: 'AdminLayout', requiresAuth: true, requiresAdmin: true } },
    { path: '/admin/users/create', component: UserCreate, name: 'users.create', meta: { layout: 'AdminLayout', requiresAuth: true, requiresAdmin: true } },
    { path: '/admin/users/:id/edit', component: UserEdit, name: 'users.edit', meta: { layout: 'AdminLayout', requiresAuth: true, requiresAdmin: true } },
    { path: '/admin/users/:id', component: UserShow, name: 'users.show', meta: { layout: 'AdminLayout', requiresAuth: true, requiresAdmin: true } }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});


router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token');
    const userStr = localStorage.getItem('user');
    const user = userStr ? JSON.parse(userStr) : null;
    const isAdmin = user && user.email === 'admin@blog.com';
    
   
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isAuthenticated) return next('/login');
    }
    
   
    if (to.matched.some(record => record.meta.requiresAdmin)) {
        if (!isAdmin) {
             return next('/'); 
        }
    }
    

    if (to.matched.some(record => record.meta.guestOnly)) {
        if (isAuthenticated) {
            if(isAdmin) return next('/admin');
            return next('/');
        }
    }
    
    next();
});

export default router;
