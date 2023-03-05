import { createRouter, createWebHistory } from "vue-router";
import UserList from "@/views/UserList.vue";
import UserDetail from "@/views/UserDetail.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
        path: '/',
        name: 'home',
        component: UserList,
    },
    {
      path: '/users',
      name: 'users',
      component: UserList,
    },
    {
        path: '/users/:id',
        component: UserDetail
    }
  ],
});

export default router;
