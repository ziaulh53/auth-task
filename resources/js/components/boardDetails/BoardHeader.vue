<template>
    <div class="bg-orange-200 px-[40px] md:px-[80px]">
        <div class="flex justify-between h-[60px] items-center">
            <div>{{ boardData?.title }}</div>
            <div class="flex items-center">
                <div class="mr-2">
                    <img :src="'/user.png'" class="w-[30px] h-30px" />
                </div>
                <div class="mr-2">
                    <img :src="'/user.png'" class="w-[30px] h-30px" />
                </div>
                <div class="mr-2">
                    <img :src="'/user.png'" class="w-[30px] h-30px" />
                </div>
                <div>
                    <button class="px-5 py-1 rounded-lg bg-slate-800 text-white font-semibold"
                        @click="() => open = true">Add
                        Member</button>
                </div>
            </div>
        </div>
    </div>
    <a-modal title="Invite to board" v-model:open="open" :okButtonProps="{ disabled: !email || loading, loading: loading }"
        :onOk="handleAddMember">
        <div class="mb-2 font-semibold">List Name</div>
        <input class="py-2 px-3 border-2 w-full rounded-lg" v-model="email" />
    </a-modal>
</template>

<script setup>
import { ref, toRefs } from 'vue';
import { api } from '../../api';
import { notify } from '../../helpers';

const props = defineProps({
    boardData: Object
})
const { boardData } = toRefs(props)

const email = ref("");
const open = ref(false);
const loading = ref(false);

const handleAddMember = async () => {
    loading.value = true;
    try {
        const res = await api.post('invite-member', { email: email.value, board_id: boardData.value.id });
        notify(res);
    } catch (error) {
        console.log(error.message);
    }
    loading.value = false;
}
</script>