<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CustomTable from '@/components/CustomTable.vue';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuarios',
        href: '/users',
    },
];

defineProps<{ users: object }>();

// columnas para la tabla
const columns = [
    { label: 'Nombre', key: 'name', sortable: true },
    { label: 'Email', key: 'email', sortable: true },
    { label: 'Tipo usuario', key: 'rol', custom: true },
    { label: 'Acciones', key: 'actions', custom: true },
];

const deleteItem = (id: string) => {
    // TODO: implementar logica de borrado
    alert('eliminado ' + id);
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
