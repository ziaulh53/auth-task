<template>
    <div class="px-[40px] md:px-[80px] bg-slate-100 border-b-2">
        <div class="flex h-[60px] items-center justify-between">
            <div class="font-bold text-xl">Logo</div>
            <div class="">
                <img :src="'/user.png'" class="w-[40px] h-[40px]" />
            </div>
        </div>
    </div>
    <div class="px-[40px] md:px-[80px]">
        <slot></slot>
    </div>
</template>