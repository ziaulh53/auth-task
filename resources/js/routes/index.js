import { createRouter, createWebHistory } from "vue-router";
import { Board, BoardDetails, ForgotPassword, Invitations, ResetPassword, Signin, Signup } from "../pages";
import { useAuthStore } from "../store";

const routes = [
    {
        path: "/",
        name: "signin",
        component: Signin,
        meta: {
            requireAuth: false,
        },
    },
    {
        path: "/signup",
        name: "signup",
        component: Signup,
        meta: {
            requireAuth: false,
        },
    },
    {
        path: "/forget-password",
        name: "forget-password",
        component: ForgotPassword,
        meta: {
            requireAuth: false,
        },
    },
    {
        path: "/reset-password",
        name: "reset-password",
        component: ResetPassword,
        meta: {
            requireAuth: false,
        },
    },
    {
        path: "/boards",
        name: "board",
        component: Board,
        meta: {
            requireAuth: true,
        },
    },
    {
        path: "/invitations",
        name: "invitaions",
        component: Invitations,
        meta: {
            requireAuth: true,
        },
    },
    {
        path: "/boards/:id",
        name: "board-details",
        component: BoardDetails,
        meta: {
            requireAuth: true,
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const userStore = useAuthStore();
    const { isAuthenticated } = userStore.user;
    if (to.meta.requireAuth && !isAuthenticated) {
        next("/");
    } else if (!to.meta.requireAuth && isAuthenticated) {
        next("/boards");
    } else next();
});

export default router;
