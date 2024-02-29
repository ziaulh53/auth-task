<template>
    <div class="flex items-center justify-center h-screen">
        <div class="w-[400px] border-2 p-5 shadow-lg bg-slate-100">
            <div class="mb-4">
                <div class="font-semibold">Email</div>
                <input class="w-full border-2 px-4 py-2 rounded-md" name="email" placeholder="Email" v-model="email" />
            </div>
            <div class="mb-2">
                <button class="w-full p-2 bg-blue-950 text-white rounded-lg disabled:bg-blue-200" :disabled="disabled"
                    @click="handleSubmit">{{loading ? 'Loading...':"Submit"}}</button>
            </div>
        </div>
    </div>
</template>


<script setup>

import { computed, ref } from 'vue';
import { useAuthStore } from '../store'
const emailRegx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
const userStore = useAuthStore();

const email = ref('')
const loading = ref(false)
const disabled = computed(() => !email.value || !emailRegx.test(email.value));

const handleSubmit = async () => {
    loading.value = true
    try {
        await userStore.forgetPassword({ email: email.value });
    } catch (error) {
        console.log(error)
    }
    loading.value = false

}

</script>