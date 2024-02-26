<template>
    <div class="px-[40px] md:px-[80px] bg-slate-100 border-b-2">
        <div class="flex h-[60px] items-center justify-between">
            <div class="font-bold text-xl">Logo</div>
            <a-dropdown :trigger="['click']">
                <a class="ant-dropdown-link" @click.prevent>
                    <img :src="'/user.png'" class="w-[40px] h-[40px]" />
                </a>
                <template #overlay>
                    <a-menu>
                        <!-- <a-menu-item key="0">
                            <a href="http://www.alipay.com/">1st menu item</a>
                        </a-menu-item> -->
                        <a-menu-item key="1">
                            <RouterLink to="/invitations">Invitations</RouterLink>
                        </a-menu-item>
                        <a-menu-divider />
                        <a-menu-item key="3">
                            <button @click="handleLogout">Logout</button>
                        </a-menu-item>
                    </a-menu>
                </template>
            </a-dropdown>
        </div>
    </div>
    <div class="px-[40px] md:px-[80px]">
        <slot></slot>
    </div>
</template>

<script setup>
import { useAuthStore } from '../store'

const userStore = useAuthStore();

const handleLogout = async () => {
    userStore.userLogout();
}
</script>