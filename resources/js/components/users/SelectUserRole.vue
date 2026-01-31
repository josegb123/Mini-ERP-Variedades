<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

export interface Role {
    value: string;
    label: string;
}

interface Props {
    modelValue: string;
    error?: string;
}

const props = defineProps<Props>();
const emit = defineEmits(['update:modelValue']);

/**
 * Acceso global a los roles compartidos desde Laravel (HandleInertiaRequests)
 */
const page = usePage();
const roles = computed(() => page.props.roles as Role[]);

/**
 * Writable computed para manejar el v-model de forma limpia.
 * Get: Lee la prop del padre.
 * Set: Emite el cambio automáticamente al seleccionar.
 */
const proxyValue = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
});

const inputClass = 'border-slate-400 focus-visible:ring-slate-500';
</script>

<template>
    <div class="grid gap-2">
        <Label
            for="role"
            :class="{ 'text-destructive': error }"
            class="font-medium"
        >
            Rol del Usuario
        </Label>

        <Select v-model="proxyValue">
            <SelectTrigger
                id="role"
                :class="[inputClass, { 'border-destructive': error }]"
                class="cursor-pointer transition-all hover:brightness-75"
            >
                <SelectValue placeholder="Selecciona un rol" />
            </SelectTrigger>

            <SelectContent>
                <SelectItem
                    v-for="role in roles"
                    :key="role.value"
                    :value="role.value"
                    class="cursor-pointer"
                >
                    {{ role.label }}
                </SelectItem>
            </SelectContent>
        </Select>

        <Transition name="fade">
            <p v-if="error" class="text-xs font-medium text-destructive">
                {{ error }}
            </p>
        </Transition>
    </div>
</template>
