<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Trash, Eye, EyeOff } from 'lucide-vue-next'; // Nuevos iconos
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
import SelectUserRole from './SelectUserRole.vue';

const isOpen = ref(false);
const showPassword = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: '',
    password_confirmation: '',
});

const inputClass = 'border-slate-400 focus-visible:ring-slate-500';

const passwordsMatch = computed(() => {
    if (!form.password && !form.password_confirmation) return true;
    return form.password === form.password_confirmation;
});

const clearForm = () => {
    form.reset();
    form.clearErrors();
};

const submit = () => {
    if (!passwordsMatch.value) return;

    form.submit(store(), {
        onSuccess: () => {
            isOpen.value = false;
            toast.success(`¡Usuario "${form.name}" creado exitosamente!`);
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
                <DialogTitle class="text-xl font-semibold tracking-tight"
                    >Crear Nuevo Usuario</DialogTitle
                >
                <DialogDescription class="text-muted-foreground">
                    Completa los campos. La visibilidad de la contraseña es
                    compartida.
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

                <SelectUserRole v-model="form.role" :error="form.errors.role" />

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label
                            for="password"
                            :class="{
                                'text-destructive': form.errors.password,
                            }"
                            >Contraseña</Label
                        >
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="flex items-center gap-1 text-xs text-muted-foreground transition-colors hover:text-primary"
                        >
                            <component
                                :is="showPassword ? EyeOff : Eye"
                                class="h-3.5 w-3.5"
                            />
                            {{ showPassword ? 'Ocultar' : 'Mostrar' }}
                        </button>
                    </div>
                    <div class="relative">
                        <Input
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            placeholder="••••••••"
                            autocomplete="new-password"
                            :class="[
                                inputClass,
                                { 'border-destructive': form.errors.password },
                            ]"
                        />
                    </div>
                    <p
                        v-if="form.errors.password"
                        class="text-xs font-medium text-destructive"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>

                <div class="grid gap-2">
                    <Label
                        for="password_confirmation"
                        :class="{ 'text-destructive': !passwordsMatch }"
                    >
                        Confirmar Contraseña
                    </Label>
                    <Input
                        id="password_confirmation"
                        :type="showPassword ? 'text' : 'password'"
                        v-model="form.password_confirmation"
                        placeholder="••••••••"
                        autocomplete="new-password"
                        :class="[
                            inputClass,
                            { 'border-destructive': !passwordsMatch },
                        ]"
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
                        class="w-full cursor-pointer transition-all hover:brightness-90 sm:me-auto sm:w-auto"
                    >
                        <Trash class="mr-2 h-4 w-4" />
                        Limpiar
                    </Button>

                    <div class="flex w-full gap-3 sm:w-auto">
                        <Button
                            type="button"
                            variant="outline"
                            @click="isOpen = false"
                            class="flex-1 cursor-pointer border-slate-400 hover:brightness-75 sm:flex-none"
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
                            class="min-w-25 flex-1 cursor-pointer sm:flex-none"
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
