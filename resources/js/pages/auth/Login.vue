<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';

defineProps<{
    status?: string;
    canRegister: boolean;
}>();

const inputClass = 'border-slate-400 focus-visible:ring-slate-500';
</script>

<template>
    <AuthBase
        title="Iniciar sesión"
        description="Ingresa tu correo y contraseña para acceder al sistema"
    >
        <Head title="Iniciar sesión" />

        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Correo electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        :class="inputClass"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="correo@ejemplo.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Contraseña</Label>
                        <span class="text-xs text-muted-foreground">
                            ¿Olvidaste tu acceso? Contacta al admin
                        </span>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        :class="inputClass"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Tu contraseña"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label
                        for="remember"
                        class="flex cursor-pointer items-center space-x-3 text-sm font-medium"
                    >
                        <Checkbox
                            id="remember"
                            name="remember"
                            :tabindex="3"
                            class="border-slate-400"
                        />
                        <span>Recordarme en este equipo</span>
                    </Label>
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full cursor-pointer transition-all hover:brightness-90"
                    :tabindex="4"
                    :disabled="processing"
                    data-test="login-button"
                >
                    <Spinner v-if="processing" class="mr-2" />
                    {{
                        processing ? 'Iniciando sesión...' : 'Entrar al sistema'
                    }}
                </Button>
            </div>

            <div
                class="text-center text-sm text-muted-foreground"
                v-if="canRegister"
            >
                ¿No tienes cuenta?
                <TextLink
                    :href="register()"
                    :tabindex="5"
                    class="font-medium underline underline-offset-4"
                >
                    Solicita una aquí
                </TextLink>
            </div>
        </Form>
    </AuthBase>
</template>
