<template>
    <div class="flex items-center justify-center h-screen">
        <div class="w-[400px] border-2 p-5 shadow-lg bg-slate-100">
            <div class="mb-4">
                <div class="font-semibold">Password</div>
                <input type="password" class="w-full border-2 px-4 py-2 rounded-md" placeholder="* * * * * *"
                    @input="onChange" v-model="credentialData.password" name="password" />
            </div>
            <div class="mb-4">
                <div class="font-semibold">Confirm Password</div>
                <input type="password" class="w-full border-2 px-4 py-2 rounded-md" placeholder="* * * * * *"
                    @input="onChange" v-model="credentialData.confirmPassword" name="password" />
            </div>
            <div class="mb-2">
                <button class="w-full p-2 bg-blue-950 text-white rounded-lg disabled:bg-blue-200" :disabled="disabled"
                    @click="handleSubmit">Submit</button>
            </div>
        </div>
    </div>
</template>

<script setup>

import { computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../store';

const userStore = useAuthStore();
const route = useRoute()
const router = useRouter();

const credentialData = ref({ confirmPassword: '', password: '' })
const loading = ref(false)
const disabled = computed(() => !credentialData.value.password || credentialData.value.password !== credentialData.value.confirmPassword);

const handleSubmit = async () => {
    loading.value = true;
    try {
        const res = await userStore.resetPassword({ password: credentialData.value.password, token: route.query.token, email: route.query.email })
        if (res.success) {
            router.push({ name: 'signin' })
        }
    } catch (error) {
        console.log(error)
    }
    loading.value = false

}

</script>