<template>
    <PrivateLayout>
        <h2 class="my-3 text-xl font-semibold pb-2 border-b-2">Your invitations</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div v-for="inv of allInvitations" :key="inv.id" :class="(query?.token ===inv?.token ? 'bg-orange-300':'bg-slate-200') +' p-5 rounded-lg shadow-xl border'">
                <h3 class="font-bold mb-3">{{ inv?.board?.title }}</h3>
                <a-space />
                <div class="flex justify-between">
                    <button class="border-2 border-red-400 px-5 py-1 bg-white rounded-lg"
                        @click="() => handleCancelInvitation(inv.id)">Cancel</button>
                    <button class="border-2 border-green-800 px-5 py-1 bg-white rounded-lg"
                        @click="() => handleAcceptInvitation(inv.token)">Accept</button>
                </div>
            </div>
        </div>
        <div v-if="allInvitations.length === 0" class="text-center text-gray-400">
            You have no invitations
        </div>
    </PrivateLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import PrivateLayout from '../layout/PrivateLayout.vue';
import { api } from '../api';
import { useRouter, useRoute } from 'vue-router';
import { notify } from '../helpers';

const router = useRouter();
const route = useRoute();
const query = route.query;
const allInvitations = ref([]);
const loading = ref(false);
const fetchInvitations = async () => {
    loading.value = true;
    try {
        const res = await api.get('/invitation');
        allInvitations.value = res.invitations;
    } catch (error) {
        console.log(error.message);
    }
    loading.value = false;

}
const handleAcceptInvitation = async (token) => {
    try {
        const res = await api.post('invite-accept', { token });
        if (res.success) {
            router.push('/boards/' + res?.board_id);
        }
    } catch (error) {
        console.log(error.message);
    }
}

const handleCancelInvitation = async (id) => {
    try {
        const res = await api.post('invite-cancel', { invitation_id: id });
        notify(res, fetchInvitations);
    } catch (error) {
        console.log(error.message);
    }
}

onMounted(() => {
    fetchInvitations();
})
</script>