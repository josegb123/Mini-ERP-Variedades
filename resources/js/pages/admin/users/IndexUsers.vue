<script setup lang="ts">
import { Head, usePage, router } from '@inertiajs/vue3';
import { watch } from 'vue';
import { toast } from 'vue-sonner';
import { destroy } from '@/actions/App/Http/Controllers/UserController';
import CustomTable from '@/components/CustomTable.vue';
import Button from '@/components/ui/button/Button.vue';

import AppLayout from '@/layouts/AppLayout.vue';
import {type User, type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuarios',
        href: '/users',
    },
];

const page = usePage();

watch(
    // Accedemos a la raíz del objeto page, no a props
    () => page.flash,
    (flash) => {
        // Verificamos que el mensaje exista
        if (flash?.message) {
            if (flash.status === 'success') {
                toast.success(flash.message);
            } else if (flash.status === 'error' || flash.status === 'fail') {
                toast.error(flash.message);
            } else {
                toast(flash.message); // Notificación genérica
            }
        }
    },
    { deep: true, immediate: true },
);

defineProps<{ users: User }>();

// columnas para la tabla
const columns = [
    { label: 'Nombre', key: 'name', sortable: true },
    { label: 'Email', key: 'email', sortable: true },
    { label: 'Tipo usuario', key: 'rol', custom: true },
    { label: 'Acciones', key: 'actions', custom: true },
];

const deleteItem = (id: string) => {
    // TODO: implementar logica de borrado
    if (confirm('estas seguro')) {
        router.delete(destroy(id));
    }
};

const editItem = (id: string) => {
    // TODO: implementar logica de edicion
    alert('editado ' + id);
};
</script>

<template>
    <Head title="Usuarios" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <CustomTable :columns="columns" :data="users">
                <template #actions="{ item }">
                    <div class="flex gap-2">
                        <Button
                            size="sm"
                            variant="outline"
                            @click="editItem(item.id)"
                        >
                            Editar
                        </Button>

                        <Button
                            size="sm"
                            variant="destructive"
                            @click="deleteItem(item.id)"
                        >
                            Borrar
                        </Button>
                    </div>
                </template>
            </CustomTable>
        </div>
    </AppLayout>
</template>
