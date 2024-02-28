<template>
    <PrivateLayout />
    <BoardHeader :boardData="boardData" :members="members" :isOwner="isOwner" :refetch="fetchBoard" />
    <div class="overflow-x-auto whitespace-nowrap rounded p-4 w-full relative">
        <draggable class="inline-flex space-x-4 items-start" v-model="allLists" group="list" @start="drag = true"
            item-key="id" :ondragend="onDragEnd">
            <template #item="{ element }">
                <div class="w-[284px] bg-slate-100 p-2 rounded border max-h-[75vh] overflow-x-auto">
                    <BoardList :data="element" :refetch="fetchLists" :isOwner="isOwner" :web-user="userStore.user?.user" />
                </div>
            </template>
        </draggable>
        <button v-if="!accessDenied" class="w-[284px] bg-slate-300 rounded ms-4 text-black py-2" @click="() => open = true">Add List</button>
    </div>

    <div v-if="accessDenied" class="text-center mt-10">
        <RouterLink to="/boards"><span class="px-4 py-2 bg-blue-700 text-white">Go to board list</span></RouterLink>
    </div>

    <a-modal title="Create List" v-model:open="open"
        :okButtonProps="{ disabled: disabled || addLoading, loading: addLoading }" :onOk="handleCreateList">
        <div class="mb-2 font-semibold">List Name</div>
        <input class="py-2 px-3 border-2 w-full rounded-lg" v-model="title" />
    </a-modal>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import PrivateLayout from '../layout/PrivateLayout.vue';
import { BoardHeader, BoardList } from '../components/boardDetails';
import { api } from '../api';
import { useRoute } from 'vue-router';
import draggable from 'vuedraggable';
import { useAuthStore } from '../store';

const route = useRoute();
const boardData = ref({});
const members = ref([]);
const allLists = ref([]);
const title = ref("");
const open = ref(false);
const loading = ref(false);
const addLoading = ref(false);
const isOwner = ref(false);
const accessDenied = ref(false);

const userStore = useAuthStore();

const disabled = computed(() => !title.value);

const fetchBoard = async () => {
    loading.value = true;
    try {
        const res = await api.get('board/' + route.params.id);
        boardData.value = res?.board;
        members.value = res?.members;
        isOwner.value = res?.board?.user_id === userStore.user?.user?.id;
        if (!res?.success) {
            accessDenied.value = true;
        }
    } catch (error) {
        console.log(error.message);
    }
    loading.value = false;
}

const fetchLists = async () => {
    loading.value = true;
    try {
        const res = await api.get('list-board/' + route.params.id);
        allLists.value = res?.lists;
    } catch (error) {
        console.log(error.message);
    }
    loading.value = false;
}

const handleCreateList = async () => {
    try {
        const res = await api.post('/list', { title: title.value, board_id: route.params.id, order: allLists.value?.length })
        if (res.success) {
            onClose();
            fetchLists();
        }
    } catch (error) {
        console.log(error.message);
    }
}

const onDragEnd = async (event) => {
    const { newIndex, oldIndex } = event;
    const reorderedLists = Array.from(allLists.value);
    reorderedLists.splice(newIndex, 0, reorderedLists.splice(oldIndex, 1)[0]);
    allLists.value = reorderedLists;
    await api.post('update-list-order', { lists: reorderedLists.map(list => list.id) });
};

onMounted(() => {
    fetchBoard();
    fetchLists();
})

const onClose = () => {
    title.value = "";
    open.value = false;
}


</script>