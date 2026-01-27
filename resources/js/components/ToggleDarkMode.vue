<script setup lang="ts">
import { Sun, Moon } from 'lucide-vue-next';
import { computed } from 'vue';
import {
    SidebarMenu,
    SidebarMenuItem,
    SidebarMenuButton,
} from '@/components/ui/sidebar';
import { useAppearance } from '@/composables/useAppearance';

const { appearance, updateAppearance } = useAppearance();
const isDark = computed(() => appearance.value === 'dark');

const toggleTheme = () => {
    updateAppearance(isDark.value ? 'light' : 'dark');
};
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <SidebarMenuButton
                tooltip="Cambiar tema"
                @click="toggleTheme"
                class="cursor-pointer"
            >
                <Sun v-if="isDark" class="h-5 w-5" />
                <Moon v-else class="h-5 w-5" />

                <span>{{ isDark ? 'Modo Oscuro' : 'Modo Claro' }}</span>
            </SidebarMenuButton>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
