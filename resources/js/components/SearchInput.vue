<script setup lang="ts">
import { Search } from 'lucide-vue-next';

defineProps<{
    modelValue: string;
}>();

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="relative w-full">
        <Search
            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
        />
        <input
            :value="modelValue"
            @input="
                emit(
                    'update:modelValue',
                    ($event.target as HTMLInputElement).value,
                )
            "
            type="text"
            placeholder="Buscar..."
            class="h-10 w-full rounded-md border border-input bg-background pr-4 pl-10 text-sm outline transition-all focus-visible:ring-2 focus-visible:ring-ring"
        />
    </div>
</template>
