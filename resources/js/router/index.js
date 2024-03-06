import { createRouter, createWebHistory } from 'vue-router'
import { useStore } from 'vuex'

import Dashboard from '@/Pages/Dashboard.vue'
import Expenses from '@/Pages/Expenses/Index.vue'
import Login from '@/Pages/Auth/Login.vue'
import Register from '@/Pages/Auth/Register.vue'
import UploadFile from '@/Pages/UploadFile/index.vue'

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/register',
    name: 'register',
    component: Register
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    meta: {
      requiresAuth: true
    },
    component: Dashboard
  },
  {
    path: '/upload',
    name: 'upload',
    meta: {
      requiresAuth: true
    },
    component: UploadFile
  },
  {
    path: '/expenses',
    name: 'expenses',
    meta: {
      requiresAuth: true
    },
    component: Expenses
  },
  {
    path: '/:catchAll(.*)',
    name: 'Not Found'
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

const freePages = ['login', 'register']

router.beforeEach((to, from, next) => {
  const store = useStore()

  if (to.meta.requiresAuth && !store.getters['auth/isLoggedIn']) {
    next({ name: 'login' })
  } else if (freePages.includes(to.name) && store.getters['auth/isLoggedIn']) {
    next({ name: 'dashboard' })
  }
  next()
})

export default router
