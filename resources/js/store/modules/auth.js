import { defineStore } from "pinia";
import { api, auth } from "../../api";
import { useLocalStorage } from "@vueuse/core";
import { notify } from "../../helpers";


export const useAuthStore = defineStore("auth", {
    state: () => ({
        auth: useLocalStorage("auth", {
            isAuthenticated: false,
            user: {},
            token: "",
        }),
    }),
    getters: {
        user: (state) => state.auth,
    },
    actions: {
        async userRegistration(endpoint, data) {
            try {
                const res = await api.post(endpoint, data);
                notify(res);
                return res;
            } catch (error) {
                console.log(error);
            }
        },
        async userLogin(endpoint, data) {
            try {
                const res = await api.post(endpoint, data);
                if (res.success) {
                    this.auth = {
                        isAuthenticated: true,
                        user: res?.user,
                        token: res?.token,
                    };
                }
                notify(res);
                return res;
            } catch (error) {
                console.log(error);
            }
        },
        async userLogout(endpoint) {
            try {
                const res = await api.post('/signout');
                if (res.success) {
                    this.auth = {
                        isAuthenticated: false,
                        user: {},
                        token: "",
                    };
                    window.location.reload();
                }
            } catch (error) {
                console.log(error);
            }
        },
        async forgetPassword(data) {
            try {
                const res = await api.post(auth.forgetPassword, data);
                notify(res);
            } catch (error) {
                console.log(error);
            }
        },
        async resetPassword(data) {
            try {
                const res = await api.post(auth.resetPassword, data);
                notify(res);
                return res;
            } catch (error) {
                console.log(error);
            }
        },
        async updateProfile(data) {
            try {
                const res = await api.post(auth.editProfile, {
                    ...data,
                });
                notify(res);
                if (res.success) {
                    this.auth = {
                        ...this.auth,
                        user: { ...this.auth.user, ...data },
                    };
                }
                return res;
            } catch (error) {
                console.log(error);
            }
        },
        async changeEmail(data) {
            try {
                const res = await api.post(auth.changeEmailRequest, data);
                notify(res);
                return res;
            } catch (error) {
                console.log(error);
            }
        },
        async changePassword(data) {
            try {
                const res = await api.post(auth.changePassword, { ...data });
                notify(res);
                return res;
            } catch (error) {
                console.log(error);
            }
        },
    },
});