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
                    <button class="px-5 py-1 rounded-lg bg-slate-800 text-white font-semibold">Add Member</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    boardData: Object
})
</script>