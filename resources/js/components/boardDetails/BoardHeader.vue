<template>
    <div class="bg-slate-800 px-[40px] md:px-[80px]">
        <div class="flex justify-between h-[60px] items-center">
            <div class="text-white">{{ boardData?.title }}</div>
            <div class="flex items-center">
                <div class="mr-2">
                    <a-popover placement="top">
                        <template #content>
                            {{ isOwner ? 'You' : 'Owner' }}
                        </template>
                        <img :src="'/user.png'" class="w-[30px] h-30px" />
                    </a-popover>
                </div>
                <div v-for="member of members" :key="member.id" class="mr-2">
                    <a-popover placement="top">
                        <template #content>
                            {{ member?.name }}
                        </template>
                        <a-dropdown :disabled="!isOwner" :trigger="['click']">
                            <a class="ant-dropdown-link cursor-pointer" @click.prevent>
                                <img :src="'/user.png'" class="w-[30px] h-[30px]" />
                            </a>
                            <template #overlay>
                                <a-menu>
                                    <a-menu-item key="3">
                                        <button @click="() => handleRemoveMember(member?.pivot?.user_id)">Remove</button>
                                    </a-menu-item>
                                </a-menu>
                            </template>
                        </a-dropdown>

                    </a-popover>
                </div>
                <div v-if="isOwner">
                    <button class="px-5 py-1 rounded-lg bg-slate-300 text-black text-sm font-semibold"
                        @click="() => open = true">Add
                        Member</button>
                </div>
            </div>
        </div>
    </div>
    <a-modal title="Invite to board" v-model:open="open" :okButtonProps="{ disabled: !email || loading, loading: loading }"
        :onOk="handleAddMember">
        <div class="mb-2 font-semibold">Email</div>
        <input class="py-2 px-3 border-2 w-full rounded-lg" v-model="email" type="email" />
    </a-modal>
</template>

<script setup>
import { ref, toRefs } from 'vue';
import { api } from '../../api';
import { notify } from '../../helpers';

const props = defineProps({
    boardData: Object,
    members: Array,
    isOwner: Boolean,
    refetch: Function
})
const { boardData, refetch } = toRefs(props)

const email = ref("");
const open = ref(false);
const loading = ref(false);

const handleAddMember = async () => {
    loading.value = true;
    try {
        const res = await api.post('invite-member', { email: email.value, board_id: boardData.value.id });
        notify(res, ()=>open.value=false);
    } catch (error) {
        console.log(error.message);
    }
    loading.value = false;
}

const handleRemoveMember = async (id) => {
    try {
        const res = await api.post('member-delete', { board_id: boardData.value.id, member_id: id });
        notify(res, refetch.value)
    } catch (error) {
        console.log(error.message);
    }
}
</script>