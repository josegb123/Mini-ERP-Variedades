<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DeleteUser from '@/components/DeleteUser.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/profile';
import { type BreadcrumbItem } from '@/types';

const inputClass = 'border-slate-400 focus-visible:ring-slate-500';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Configuración de perfil',
        href: edit().url,
    },
];

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Perfil" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <Heading
                    variant="small"
                    title="Información del perfil"
                    description="Actualiza tu nombre y dirección de correo electrónico"
                />

                <Form
                    v-bind="ProfileController.update.form()"
                    class="space-y-6"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="grid gap-2">
                        <Label for="name">Nombre completo</Label>
                        <Input
                            id="name"
                            :class="inputClass"
                            name="name"
                            :default-value="user.name"
                            required
                            autocomplete="name"
                            placeholder="Tu nombre completo"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Correo electrónico</Label>
                        <Input
                            id="email"
                            type="email"
                            :class="inputClass"
                            name="email"
                            :default-value="user.email"
                            required
                            autocomplete="username"
                            placeholder="correo@ejemplo.com"
                        />
                        <InputError :message="errors.email" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button
                            :disabled="processing"
                            class="cursor-pointer transition-all hover:brightness-90"
                            >Guardar cambios</Button
                        >

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="recentlySuccessful"
                                class="text-sm font-medium text-green-600"
                            >
                                Cambios guardados con éxito.
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
