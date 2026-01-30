<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Pencil, Eye, EyeOff, RotateCcw } from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';
import { toast } from 'vue-sonner';
import { update } from '@/actions/App/Http/Controllers/UserController';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const isOpen = ref(false);
const showPassword = ref(false);

/**
 * High-contrast style for inputs to improve visibility
 */
const inputClass = 'border-slate-400 focus-visible:ring-slate-500';

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
});

/**
 * Synchronize internal form state with props for SPA navigation
 */
watch(
    () => props.user,
    (newUser) => {
        form.name = newUser.name;
        form.email = newUser.email;
    },
    { deep: true },
);

const passwordsMatch = computed(() => {
    if (!form.password && !form.password_confirmation) return true;
    return form.password === form.password_confirmation;
});

const submit = () => {
    if (!passwordsMatch.value) return;

    form.submit(update(props.user.id), {
        onSuccess: () => {
            isOpen.value = false;
            toast.success('Usuario actualizado correctamente');
            form.reset('password', 'password_confirmation');
        },
        onError: () => form.reset('password', 'password_confirmation'),
        preserveScroll: true,
    });
};

const clearErrors = () => {
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <Button
                variant="outline"
                size="icon"
                class="h-9 w-auto cursor-pointer px-3 transition-all hover:brightness-75"
            >
                <Pencil class="mr-2 h-4 w-4" />
                Editar
            </Button>
        </DialogTrigger>

        <DialogContent class="gap-6 sm:max-w-106.25">
            <DialogHeader>
                <DialogTitle class="text-xl font-semibold tracking-tight">
                    Editar Usuario
                </DialogTitle>
                <DialogDescription class="text-muted-foreground">
                    Modifica los datos del usuario. Deja la contraseña en blanco
                    si no deseas cambiarla.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="grid gap-2">
                    <Label
                        for="edit-name"
                        :class="{ 'text-destructive': form.errors.name }"
                        >Nombre</Label
                    >
                    <Input
                        id="edit-name"
                        v-model="form.name"
                        autocomplete="name"
                        :class="[
                            inputClass,
                            { 'border-destructive': form.errors.name },
                        ]"
                    />
                    <p
                        v-if="form.errors.name"
                        class="text-xs font-medium text-destructive"
                    >
                        {{ form.errors.name }}
                    </p>
                </div>

                <div class="grid gap-2">
                    <Label
                        for="edit-email"
                        :class="{ 'text-destructive': form.errors.email }"
                        >Email</Label
                    >
                    <Input
                        id="edit-email"
                        type="email"
                        v-model="form.email"
                        autocomplete="email"
                        :class="[
                            inputClass,
                            { 'border-destructive': form.errors.email },
                        ]"
                    />
                    <p
                        v-if="form.errors.email"
                        class="text-xs font-medium text-destructive"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label
                            for="edit-password"
                            :class="{
                                'text-destructive': form.errors.password,
                            }"
                        >
                            Nueva Contraseña (Opcional)
                        </Label>
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="flex cursor-pointer items-center gap-1 text-xs text-muted-foreground transition-colors hover:text-primary"
                        >
                            <component
                                :is="showPassword ? EyeOff : Eye"
                                class="h-3.5 w-3.5"
                            />
                            {{ showPassword ? 'Ocultar' : 'Mostrar' }}
                        </button>
                    </div>
                    <Input
                        id="edit-password"
                        :type="showPassword ? 'text' : 'password'"
                        v-model="form.password"
                        placeholder="••••••••"
                        autocomplete="new-password"
                        :class="[
                            inputClass,
                            { 'border-destructive': form.errors.password },
                        ]"
                    />
                    <p
                        v-if="form.errors.password"
                        class="text-xs font-medium text-destructive"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>

                <div class="grid gap-2">
                    <Label
                        for="edit-confirm"
                        :class="{ 'text-destructive': !passwordsMatch }"
                        >Confirmar Nueva Contraseña</Label
                    >
                    <Input
                        id="edit-confirm"
                        :type="showPassword ? 'text' : 'password'"
                        v-model="form.password_confirmation"
                        placeholder="••••••••"
                        autocomplete="new-password"
                        :class="[
                            inputClass,
                            { 'border-destructive': !passwordsMatch },
                        ]"
                    />
                    <p
                        v-if="!passwordsMatch"
                        class="text-xs font-medium text-destructive"
                    >
                        Las contraseñas no coinciden.
                    </p>
                </div>

                <div
                    class="mt-6 flex flex-col-reverse items-center gap-3 sm:flex-row sm:justify-end"
                >
                    <Button
                        type="button"
                        variant="secondary"
                        @click="clearErrors"
                        class="w-full cursor-pointer transition-all hover:brightness-75 sm:me-auto sm:w-auto"
                    >
                        <RotateCcw class="mr-2 h-4 w-4" />
                        Resetear
                    </Button>

                    <div class="flex w-full gap-3 sm:w-auto">
                        <Button
                            type="button"
                            variant="ghost"
                            @click="isOpen = false"
                            class="flex-1 cursor-pointer transition-all hover:brightness-75 sm:flex-none"
                        >
                            Cancelar
                        </Button>
                        <Button
                            type="submit"
                            :disabled="form.processing || !passwordsMatch"
                            class="min-w-25 flex-1 cursor-pointer transition-all hover:brightness-90 sm:flex-none"
                        >
                            <span
                                v-if="form.processing"
                                class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"
                            ></span>
                            {{ form.processing ? 'Guardando' : 'Actualizar' }}
                        </Button>
                    </div>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
