<template>
    <div class="flex justify-between mb-4">
        <h4>{{ data?.title }}</h4>
        <div v-if="isOwner">
            <span class="mr-2 text-sm cursor-pointer" @click="openModal"><i class="fas fa-edit"></i></span>
            <span class="text-sm text-red-500 cursor-pointer" @click="handleDeleteList"><i class="fas fa-trash"></i></span>
        </div>
    </div>
    <draggable v-model="data.cards" group="cards" @start="drag = true" item-key="id" @end="onDragEnd">
        <template #item="{ element }">
            <div v-bind:data-list-id="data.id">
                <ListCard :data="element" :refetch="refetch" :isOwner="isOwner" :board-id="data.board_id"/>
            </div>

        </template>
    </draggable>
    <div v-if="visibleAddCard">
        <textarea class="py-2 px-3 border-2 w-full rounded-lg mb-2" v-model="cardTitle" />
        <div class="flex items-center">
            <button class="bg-blue-700 text-white py-1 px-3 rounded-lg mr-5" @click="handleCreateCard">Add</button>
            <span class="cursor-pointer text-lg" @click="() => onClose()"><i class="fas fa-close"></i></span>
        </div>
    </div>
    <button v-if="!visibleAddCard" class="w-full bg-opacity-25 bg-red-700 py-2 rounded-lg"
        @click="() => visibleAddCard = true">Add
        Card</button>
    <a-modal title="Edit List" v-model:open="open" :okButtonProps="{ disabled: !title || loading, loading: loading }"
        :onOk="handleUpdate">
        <div class="mb-2 font-semibold">List title</div>
        <input class="py-2 px-3 border-2 w-full rounded-lg" v-model="title" />
    </a-modal>
</template>

<script setup>
import { toRefs, ref } from 'vue';
import { api } from '../../api';
import draggable from 'vuedraggable';
import ListCard from './ListCard.vue';

const props = defineProps({
    data: Object,
    refetch: Function,
    isOwner: Boolean
})

const { refetch, data } = toRefs(props);

const title = ref(data.value.title);
const cardTitle = ref("");
const loading = ref(false);
const open = ref(false);
const visibleAddCard = ref(false);

const handleDeleteList = async () => {
    try {
        const res = await api.delete('list/', data.value.id);
        if (res.success) {
            refetch.value();
        }
    } catch (error) {
        console.log(error.message);
    }
}

const handleUpdate = async () => {
    loading.value = true;
    try {
        const res = await api.put('list/', data.value.id, { title: title.value });
        if (res.success) {
            onClose();
            refetch.value();
        }
    } catch (error) {
        console.log(error.message);
    }
    loading.value = false;
}

const handleCreateCard = async () => {
    try {
        const res = await api.post('card', { task_list_id: data.value.id, title: cardTitle.value });
        if (res.success) {
            refetch.value();
            cardTitle.value = "";
            visibleAddCard.value = false
        }
    } catch (error) {
        console.log(error.message);
    }
}

const onDragEnd = (event) => {
    const fromListId = Number(data.value.id);
    const toListId = Number(event.to.firstChild?.dataset?.listId);
    const oldIndex = Number(event.oldIndex);
    const newIndex = Number(event.newIndex);
    api.post('update-card-order', { fromListId, toListId, oldIndex, newIndex });
}


// control modal
const openModal = () => {
    open.value = true;
}



const onClose = () => {
    title.value = "";
    open.value = false;
    visibleAddCard.value = false;
    cardTitle.value = ""
}
</script>