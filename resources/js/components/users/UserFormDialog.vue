<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import { store } from '@/actions/App/Http/Controllers/UserController';
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

const isOpen = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const passwordsMatch = computed(() => {
    // Si ambos campos están vacíos, no mostramos error aún
    if (!form.password && !form.password_confirmation) return true;
    return form.password === form.password_confirmation;
});

const clearForm = () => {
    form.reset();
    form.clearErrors();
};

const submit = () => {
    // Bloqueo de seguridad extra en el submit
    if (!passwordsMatch.value) return;

    form.submit(store(), {
        onSuccess: () => {
            isOpen.value = false;
            toast.success('Usuario creado');
            form.reset();
        },
        onError: () => {
            form.reset('password', 'password_confirmation');
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <Button
                class="h-10.5 px-4 font-medium transition-all active:scale-95"
            >
                <span class="mr-2">+</span> Nuevo Usuario
            </Button>
        </DialogTrigger>

        <DialogContent class="gap-6 sm:max-w-106.25">
            <DialogHeader>
                <DialogTitle class="text-xl font-semibold tracking-tight">
                    Crear Nuevo Usuario
                </DialogTitle>
                <DialogDescription class="text-muted-foreground">
                    Completa los campos. Asegúrate de que las contraseñas
                    coincidan.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="grid gap-2">
                    <Label
                        for="name"
                        :class="{ 'text-destructive': form.errors.name }"
                        >Nombre Completo</Label
                    >
                    <Input
                        id="name"
                        v-model="form.name"
                        placeholder="Ej. John Doe"
                        autocomplete="name"
                        :class="{ 'border-destructive': form.errors.name }"
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
                        for="email"
                        :class="{ 'text-destructive': form.errors.email }"
                        >Correo Electrónico</Label
                    >
                    <Input
                        id="email"
                        type="email"
                        v-model="form.email"
                        placeholder="john@example.com"
                        autocomplete="email"
                        :class="{ 'border-destructive': form.errors.email }"
                    />
                    <p
                        v-if="form.errors.email"
                        class="text-xs font-medium text-destructive"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <Label
                    for="password"
                    :class="{ 'text-destructive': form.errors.password }"
                    >Contraseña</Label
                >
                <Input
                    id="password"
                    type="password"
                    v-model="form.password"
                    placeholder="••••••••"
                    :class="{ 'border-destructive': form.errors.password }"
                />
                <p
                    v-if="form.errors.password"
                    class="text-xs font-medium text-destructive"
                >
                    {{ form.errors.password }}
                </p>

                <div class="grid gap-2">
                    <Label
                        for="password_confirmation"
                        :class="{ 'text-destructive': !passwordsMatch }"
                    >
                        Confirmar Contraseña
                    </Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        placeholder="••••••••"
                        :class="{ 'border-destructive': !passwordsMatch }"
                    />
                    <Transition name="fade">
                        <p
                            v-if="!passwordsMatch"
                            class="text-xs font-medium text-destructive"
                        >
                            Las contraseñas no coinciden.
                        </p>
                    </Transition>
                </div>

                <div
                    class="mt-6 flex flex-col-reverse items-center gap-3 sm:flex-row"
                >
                    <Button
                        type="button"
                        variant="destructive"
                        @click="clearForm"
                        class="w-full sm:me-auto sm:w-auto"
                    >
                        <Trash />
                        Limpiar
                    </Button>

                    <div class="flex w-full gap-3 sm:w-auto">
                        <Button
                            type="button"
                            variant="outline"
                            @click="isOpen = false"
                            class="flex-1 sm:flex-none"
                        >
                            Cancelar
                        </Button>

                        <Button
                            type="submit"
                            :disabled="
                                form.processing ||
                                !passwordsMatch ||
                                !form.password
                            "
                            class="min-w-25 flex-1 sm:flex-none"
                        >
                            <span
                                v-if="form.processing"
                                class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"
                            ></span>
                            {{
                                form.processing ? 'Procesando' : 'Crear Usuario'
                            }}
                        </Button>
                    </div>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
