<script setup lang="ts">
import { Sun, Moon } from 'lucide-vue-next';
import { computed } from 'vue';
import { useAppearance } from '@/composables/useAppearance';

import Toggle from './ui/toggle/Toggle.vue';

// 1. Instanciamos el composable en la raíz para evitar el warning de onMounted
const { appearance, updateAppearance } = useAppearance();

// 2. Usamos una propiedad computada para saber si estamos en modo oscuro
const isDark = computed(() => appearance.value === 'dark');

// 3. Función simplificada para el cambio
const toggleTheme = () => {
    const nextMode = isDark.value ? 'light' : 'dark';
    updateAppearance(nextMode);
};
</script>

<template>
    <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
        <Toggle
            @click="toggleTheme()"
            aria-label="Cambiar tema"
            class="rounded-full px-4"
        >
            <Sun v-if="isDark" class="h-5 w-5" />
            <Moon v-else class="h-5 w-5" />
            {{ isDark ? 'Oscuro' : 'Claro' }}
        </Toggle>
    </div>
</template>
