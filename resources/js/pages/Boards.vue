<template>
    <PrivateLayout>
        <div class="flex items-center justify-between border-b-2 pb-5 mb-4">
            <h2 class="text-xl font-semibold">Your Projects</h2>
            <button class="bg-blue-950 text-white px-4 py-1 rounded-lg" @click="() => open = true">Create New</button>
        </div>

        <a-spin :spinning="loading">
            <div class="grid grid-cols-4 gap-5">
                <router-link v-for="board of allBoard" :key="board.id" :to="'/boards/' + board.id">
                    <div class="bg-slate-200 p-5 rounded-lg">
                        <h3 class="font-bold mb-3">{{ board?.title }}</h3>
                        <div class="flex justify-between">
                            <span @click="() => onOpenModal(board)"><i class="fas fa-edit text-gray-700"></i></span>
                            <span @click="() => handleDelete(board.id)"><i class="fas fa-trash text-red-500"></i></span>
                        </div>
                    </div>
                </router-link>
            </div>
        </a-spin>

        <a-modal :title="boardId ? 'Edit Project' : 'Create Project'" v-model:open="open"
            :okButtonProps="{ disabled: disabled || addLoading, loading: addLoading }"
            :onOk="boardId ? handleUpdate : handleSubmit">
            <div class="mb-2 font-semibold">Project Name</div>
            <input class="py-2 px-3 border-2 w-full rounded-lg" v-model="title" />
        </a-modal>
    </PrivateLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import PrivateLayout from '../layout/PrivateLayout.vue';
import { api } from '../api'
import { notify } from '../helpers'

const open = ref(false);
const loading = ref(false);
const addLoading = ref(false);
const title = ref("");
const boardId = ref("");
const allBoard = ref([]);
const disabled = computed(() => !title.value);

const handleSubmit = async () => {
    addLoading.value = true;
    try {
        const res = await api.post('board', { title: title.value });
        notify(res, onClose, fetchBoards);
    } catch (error) {
        console.log(error.message);
    }
    addLoading.value = false;
}

const handleUpdate = async () => {
    addLoading.value = true;
    try {
        const res = await api.put('board/', boardId.value, { title: title.value });
        notify(res, onClose, fetchBoards);
    } catch (error) {
        console.log(error.message);
    }
    addLoading.value = false;
}

const fetchBoards = async () => {
    loading.value = true;
    try {
        const res = await api.get('board');
        allBoard.value = res?.boards || [];
    } catch (error) {
        console.log(error.message)
    }
    loading.value = false;
}

const handleDelete = async (id) => {
    try {
        const res = await api.delete('board/', id);
        notify(res, fetchBoards);
    } catch (error) {

    }
}

onMounted(() => {
    fetchBoards();
})


const onClose = () => {
    title.value = "";
    open.value = false;
    boardId.value = '';
}

const onOpenModal = data => {
    title.value = data?.title;
    boardId.value = data?.id;
    open.value = true;
}


</script>