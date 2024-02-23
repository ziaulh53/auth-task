import { createRouter, createWebHistory } from "vue-router";
// import { useAuthStore } from "../store";
import { Home } from "../pages";
import { useAuthStore } from "../store";

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
        meta: {
            requireAuth: false,
        }
    },
];

// const isAuthenticated = JSON.parse(localStorage.getItem("auth"))?.isAuthenticated;

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const userStore = useAuthStore();
    const { isAuthenticated } = userStore.user;
    if ( to.meta.requireAuth && !isAuthenticated) {
      next("/");
    } else if (
      !to.meta.requireAuth &&
      isAuthenticated
    ) {
      next("/boards");
    } else next();
});

export default router;
