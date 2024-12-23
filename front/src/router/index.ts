import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
import Home from '@/views/Home.vue';
import ChatView from '@/views/ChatView.vue';
import Login from '@/views/Login.vue';
import Register from '@/views/Register.vue';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: { requiresAuth: true },
  },
  {
    path: '/chat',
    name: 'Chat',
    component: ChatView,
    meta: { requiresAuth: true },
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { requiresGuest: true },
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { requiresGuest: true },
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/',
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authToken = localStorage.getItem('authToken');
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const requiresGuest = to.matched.some(record => record.meta.requiresGuest);

  if (requiresAuth && !authToken) {
    next({ name: 'Login' });
  } else if (requiresGuest && authToken) {
    next({ name: 'Home' });
  } else {
    next();
  }
});

export default router;