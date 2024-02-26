<template>
    <PrivateLayout>

    </PrivateLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import PrivateLayout from '../layout/PrivateLayout.vue';
import { api } from '../api';
import { useRouter } from 'vue-router';

const router = useRouter();

const allInvitations = ref([]);
const loading = ref(false);
const fetchInvitations = async () => {
    loading.value = true;
    try {
        const res = await api.get('/invitation');
        allInvitations.value = res.invitaions;
    } catch (error) {
        console.log(error.message);
    }
    loading.value = false;

}
const handleAcceptInvitation = async ()=>{
    try {
        const res = api.post('invite-accept', {token:"5b37d3c388127af08e1f2a941c1c83b20e10deaa6873a244a82b86d13032370d"});
        if(res.success){
            router.push('/boards/'+res?.board_id);
        }
    } catch (error) {
        console.log(error.message);
    }
}

onMounted(() => {
    fetchInvitations();
})
</script>