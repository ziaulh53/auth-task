<template>
    <div v-if="visibleAddCard">
        <textarea class="py-2 px-3 border-2 w-full rounded-lg mb-2" v-model="cardTitle" />
        <div class="flex items-center">
            <button class="bg-blue-700 text-white py-1 px-3 rounded-lg mr-5">Add</button>
            <span class="cursor-pointer text-lg" @click="() => onClose()"><i class="fas fa-close"></i></span>
        </div>
    </div>
    <button v-if="!visibleAddCard" class="w-full bg-opacity-25 bg-red-700 py-2 rounded-lg"
        @click="() => visibleAddCard = true">Add
        Card</button>
</template>

<script setup>
</script>