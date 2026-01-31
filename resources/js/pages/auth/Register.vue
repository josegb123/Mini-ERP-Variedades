<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';

/**
 * Obtenemos los roles de las props globales de Inertia
 */
const page = usePage();
const roles = computed(
    () => page.props.roles as { value: string; label: string }[],
);
</script>

<template>
    <AuthBase
        title="Crear una cuenta"
        description="Ingresa tus datos a continuación para registrarte"
    >
        <Head title="Registro" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Nombre completo</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        placeholder="Ej. Juan Pérez"
                        class="border-slate-400"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Correo electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="correo@ejemplo.com"
                        class="border-slate-400"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label
                        for="role"
                        :class="{ 'text-destructive': errors.role }"
                        >Rol de usuario</Label
                    >
                    <Select name="role" required>
                        <SelectTrigger
                            id="role"
                            :tabindex="3"
                            class="w-full border-slate-400 transition-all hover:brightness-90 focus-visible:ring-slate-500"
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
                    <InputError :message="errors.role" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Contraseña</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Mínimo 6 caracteres"
                        class="border-slate-400"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation"
                        >Confirmar contraseña</Label
                    >
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="5"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Repite tu contraseña"
                        class="border-slate-400"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full transition-all hover:brightness-90"
                    tabindex="6"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" class="mr-2" />
                    {{ processing ? 'Creando cuenta...' : 'Crear cuenta' }}
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                ¿Ya tienes una cuenta?
                <TextLink
                    :href="login()"
                    class="underline underline-offset-4"
                    :tabindex="7"
                    >Inicia sesión</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
