<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Trash2, AlertTriangle } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { destroy } from '@/actions/App/Http/Controllers/UserController';
import {
    AlertDialog,
    AlertDialogContent,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogCancel,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const isOpen = ref(false);
const confirmationText = ref('');
const inputClass = 'border-slate-400 focus-visible:ring-destructive';

const form = useForm({});

/**
 * Double confirmation logic:
 * Matches the user's name to prevent accidental deletions.
 */
const isConfirmed = computed(() => {
    return confirmationText.value === props.user.name;
});

const submit = () => {
    if (!isConfirmed.value) return;

    form.submit(destroy(props.user.id), {
        onSuccess: () => {
            isOpen.value = false;
            confirmationText.value = '';
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <AlertDialog v-model:open="isOpen">
        <AlertDialogTrigger as-child>
            <Button
                variant="destructive"
                size="icon"
                class="h-9 w-auto cursor-pointer px-3 transition-all hover:bg-destructive/10 hover:brightness-75"
            >
                <Trash2 class="mr-2 h-4 w-4" />
                Borrar
            </Button>
        </AlertDialogTrigger>

        <AlertDialogContent class="gap-6 sm:max-w-106.25">
            <AlertDialogHeader>
                <div class="flex items-center gap-2 text-destructive">
                    <AlertTriangle class="h-5 w-5" />
                    <AlertDialogTitle
                        class="text-xl font-semibold tracking-tight"
                    >
                        ¿Estás absolutamente seguro?
                    </AlertDialogTitle>
                </div>
                <AlertDialogDescription class="text-muted-foreground">
                    Esta acción no se puede deshacer. Esto eliminará
                    permanentemente la cuenta de
                    <span class="font-bold text-foreground">{{
                        props.user.name
                    }}</span>
                    y todos sus datos asociados.
                </AlertDialogDescription>
            </AlertDialogHeader>

            <div class="space-y-4 py-2">
                <div class="grid gap-2">
                    <Label for="confirm-text" class="text-sm">
                        Para confirmar, escribe
                        <span class="font-mono font-bold select-all">{{
                            props.user.name
                        }}</span>
                        a continuación:
                    </Label>
                    <Input
                        id="confirm-text"
                        v-model="confirmationText"
                        placeholder="Escribe el nombre exactamente"
                        :class="inputClass"
                        @keyup.enter="isConfirmed && submit()"
                    />
                </div>
            </div>

            <AlertDialogFooter class="flex-col-reverse gap-3 sm:flex-row">
                <AlertDialogCancel
                    class="mt-0 cursor-pointer border-slate-400 transition-all hover:brightness-75"
                >
                    Cancelar
                </AlertDialogCancel>
                <Button
                    variant="destructive"
                    :disabled="!isConfirmed || form.processing"
                    @click="submit"
                    class="cursor-pointer transition-all hover:brightness-75 disabled:opacity-50"
                >
                    <span
                        v-if="form.processing"
                        class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"
                    ></span>
                    {{
                        form.processing
                            ? 'Eliminando...'
                            : 'Confirmar Eliminación'
                    }}
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
