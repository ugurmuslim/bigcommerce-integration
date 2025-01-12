import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../components/CategoriesPage.vue'),
    },
    {
      path: '/categories',
      name: 'categories',
      component: () => import('../components/CategoriesPage.vue'),
    },
    {
      path: '/products/:categoryId',
      name: 'products',
      component: () => import('../components/ProductsPage.vue'),
      props: true,  // This allows you to pass route params to the component
    },
    {
      path: '/product-variants/:productId',
      name: 'product-variants',
      component: () => import('../components/VariantsPage.vue'),
      props: true,  // This allows you to pass route params to the component
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
    },
  ],
})

export default router
