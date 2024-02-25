<template>
    <div class="flex items-center justify-center h-screen">
        <div class="w-[400px] border-2 p-5 shadow-lg bg-slate-100">
            <div class="mb-4">
                <div class="font-semibold">Email</div>
                <input class="w-full border-2 px-4 py-2 rounded-md" name="email" v-model="state.email" />
            </div>
            <div class="mb-4">
                <div class="font-semibold">Password</div>
                <input type="password" class="w-full border-2 px-4 py-2 rounded-md" name="password" v-model="state.password" />
            </div>
            <div class="mb-2">
                <button class="w-full p-2 bg-blue-950 text-white rounded-lg disabled:bg-blue-200" :disabled="disabled"
                    @click="handleLogin">Signin</button>
            </div>
            <div class="">
                <p>Don't have an account? <router-link to="/signup" class="text-blue-500 hover:underline ml-3">Click
                        here</router-link></p>
                <router-link to="/forget-password" class="text-blue-500 hover:underline">Forgot
                    password?</router-link>
            </div>
        </div>
    </div>
</template>


<script setup>
import { computed, ref } from 'vue';
import { useAuthStore } from '../store';
import { useRouter } from 'vue-router';
import { notify } from '../helpers'


const userStore = useAuthStore();
const router = useRouter();

const state = ref({ email: '', password: "" });
const loading = ref(false);

const disabled = computed(() => !state.value.email || !state.value.password);



const handleLogin = async () => {
    loading.value = true;
    try {
        const res = await userStore.userLogin('signin', { ...state.value });
        if (res.success) {
            router.push({ name: 'board' })
            window.location.reload();
        }

    } catch (error) {
        console.log(error)
    }
    loading.value = false
}

</script>