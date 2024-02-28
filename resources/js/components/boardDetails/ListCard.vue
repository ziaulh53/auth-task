<template>
    <div class="bg-white rounded shadow p-3 mb-3 cursor-pointer relative" @click="handleOpenModal">
        <div v-if="JSON.parse(data?.labels)?.length > 0" class="mb-2 flex">
            <div v-for="label of JSON.parse(data?.labels)" :key="label" class="h-[10px] w-[30px] rounded-md mr-2"
                :style="{ background: label }"></div>
        </div>
        <div v-if="data?.priority !== 'Low'" class="text-end">
            <a-tag :color="data?.priority === 'High' ? 'red' : 'green'">{{ data?.priority }}</a-tag>
        </div>

        <h6 class="text-sm">{{ data?.title }}</h6>
        <div v-if="data?.assigned_users?.length > 0" class="mt-2 flex justify-end">
            <div v-for="member of data.assigned_users">
                <a-popover>
                    <template #content>
                        {{ member?.name }}({{ member?.email }})
                    </template>
                    <img :src="'/user.png'" class="w-[25px] h-[25px]" />
                </a-popover>
            </div>
        </div>
    </div>
    <a-modal width="900px" title="Task" v-model:open="open" :footer="false">
        <div class="grid grid-cols-1 mb-3">
            <label class="block mb-1 font-semibold">Card Name</label>
            <input class="py-2 px-3 border-2 w-full mb-2 rounded-lg" v-model="cardData.title" />
            <div class="text-right">
                <button class="px-3 py-1 rounded bg-slate-300 text-black" @click="handleUpdateCard">
                    Save
                </button>
            </div>
        </div>
        <h4 class="font-semibold text-sm mb-2">Assign to:</h4>
        <div class="flex items-center">
            <div v-for="member of cardData.assigned_users" class="mr-2">
                <a-popover>
                    <template #content>
                        {{ member?.name }}({{ member?.email }})
                    </template>
                    <span @click="isOwner ? () => handleRemoveAssign(member?.pivot?.user_id) : () => { }"><img
                            :src="'/user.png'" class="w-[30px] h-[30px]" /></span>
                </a-popover>
            </div>
            <button v-if="!addMemberShow && isOwner" class="px-3 py-1 rounded bg-slate-300 text-black"
                @click="() => addMemberShow = true">Add</button>
            <div v-if="addMemberShow">
                <a-select v-model:value="selectedMember" show-search placeholder="Select a member" style="width:300px"
                    :options="options" :filter-option="filterOption" @change="handleChange">
                </a-select>
                <button class="bg-blue-800 border border-blue-800 text-white px-5 py-1 rounded-md text-sm mx-2"
                    @click="handleAddAssign">Add</button>
                <button class="bg-white-800 border border-blue-800 px-5 py-1 rounded-md text-sm"
                    @click="() => addMemberShow = false">cancel</button>
            </div>
        </div>
        <a-divider class="bg-gray-300 h-[1px]" />
        <div class="grid grid-cols-2">
            <div>
                <h4 class="font-semibold text-sm mb-2">Add label:</h4>
                <a-checkbox-group v-model:value="labels" @change="handleLabelChange" style="width: 100%">
                    <a-row>
                        <a-col v-for="label of allLabels" :key="label.color" class="mb-2" :span="24">
                            <a-checkbox :value="label.color">
                                <div class="h-[20px] w-[100px] rounded-lg" :style="{ background: label.color }"></div>
                            </a-checkbox>
                        </a-col>
                    </a-row>
                </a-checkbox-group>
            </div>
            <div>
                <h4 class="font-semibold text-sm mb-2">Priority Level</h4>
                <div class="flex">
                    <a-select class="w-full mr-2" v-model:value="cardData.priority">
                        <a-select-option v-for="pr of priorityData" :key="pr">
                            {{ pr }}</a-select-option>
                    </a-select>
                    <div class="text-right">
                        <button class="px-3 py-1 rounded bg-slate-300 text-black" @click="handleUpdateCard">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <h4 class="font-semibold text-sm mb-2">Description</h4>
            <div>
                <textarea class="w-full border-2 px-3 py-2 rounded-lg mb-2 border-gray-300"
                    v-model="cardData.description" />
                <div class="text-right">
                    <button class="px-3 py-1 rounded bg-slate-300 text-black" @click="handleUpdateCard">
                        Save
                    </button>
                </div>
            </div>

        </div>
        <a-divider class="bg-gray-300 h-[1px]" />
        <div v-if="isOwner || cardData?.user_id === webUser.id" class="flex">
            <p class="mr-5 font-bold">Do you want to delete?</p>
            <div class="text-right">
                <button class="px-3 py-1 rounded bg-red-500 text-white" @click="handleDeleteCard">Delete Card</button>
            </div>
        </div>

    </a-modal>
</template>

<script setup>
import { ref, toRefs } from 'vue';
import { api } from '../../api';
import { useAuthStore } from '../../store';

const props = defineProps({
    data: Object,
    refetch: Function,
    boardId: String,
    isOwner: Boolean,
})

const { data, refetch, boardId } = toRefs(props);
const open = ref(false);
const selectedMember = ref("");
const cardData = ref({});
// const allMembers = ref({});
const options = ref([]);
const addMemberShow = ref(false);
const labels = ref([]);
const webUser = ref('');
const userStore = useAuthStore();

const fetchCard = async () => {
    try {
        const res = await api.get('card/' + data.value.id, { board_id: boardId.value });
        cardData.value = res?.card;
        // allMembers.value = res?.members
        options.value = res?.members?.map(mm => ({ label: mm?.email, value: mm.id }))
        labels.value = JSON.parse(res?.card?.labels);
        webUser.value = userStore.user?.user;
    } catch (error) {
        console.log(error.message);
    }
}

const handleDeleteCard = async () => {
    try {
        const res = await api.delete('card/', cardData.value.id);
        if (res?.success) {
            open.value = false;
            refetch.value();
        }
    } catch (error) {

    }
}

const handleUpdateCard = async () => {
    try {
        const res = await api.put('card/', cardData.value.id, { ...cardData.value });
        if (res?.success) {
            refetch.value();
            fetchCard();
        }
    } catch (error) {
        console.log(error.message)
    }
}


const handleOpenModal = () => {
    open.value = true;
    fetchCard();
}

const handleAddAssign = async () => {
    try {
        const res = await api.post('card/assign', { card_id: data.value.id, user_id: selectedMember.value });
        if (res?.success) {
            refetch.value();
            console.log('redd')
            fetchCard();
            selectedMember.value = "";
        }
    } catch (error) {
        console.log(error.message);
    }
}

const handleRemoveAssign = async (id) => {
    try {
        const res = await api.post('card/assign-remove', { card_id: data.value.id, user_id: id })
        if (res?.success) {
            refetch.value();
            fetchCard();
        }
    } catch (error) {
        console.log(error.message);
    }
}

const handleChange = (id) => {
    selectedMember.value = id;
}

const handleLabelChange = async value => {
    try {
        const res = await api.post('card/labels', { card_id: data.value.id, labels: value });
        if (res?.success) {
            fetchCard();
            refetch.value();
        }
    } catch (error) {
        console.log(error.message)
    }
}

const filterOption = (input, option) => {
    return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
};

const allLabels = [
    { label: "Black", color: "#000000" },
    { label: "Red", color: "#FF0000" },
    { label: "Green", color: "#2AAA8A" },
    { label: "Yellow", color: "#FFFF00" },
    { label: "Sky", color: "#87CEEB" },
]
const priorityData = ["Low", "Medium", "High"]
</script>