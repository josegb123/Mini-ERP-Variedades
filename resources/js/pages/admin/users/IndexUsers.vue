<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CustomTable from '@/components/CustomTable.vue';
import SearchInput from '@/components/SearchInput.vue';
import Button from '@/components/ui/button/Button.vue';
import { useTableActions } from '@/composables/actions/useTableActions';
import { useFlashMessages } from '@/composables/useFlashMessages';
import { useSearch } from '@/composables/useSearch';
import { USER_COLUMNS } from '@/config/userColumns';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

useFlashMessages();

const props = defineProps<{
    users: object;
    filters: { search: string };
}>();

const { search } = useSearch('/users', props.filters.search);

const { deleteItem, editItem } = useTableActions('users');

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Usuarios', href: '/users' }];
</script>

<template>
    <Head title="Usuarios" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <SearchInput v-model="search" />
            <CustomTable :columns="USER_COLUMNS" :data="users">
                <template #actions="{ item }">
                    <div class="flex gap-2">
                        <Button
                            variant="outline"
                            @click="editItem(item.id)"
                            class="cursor-pointer"
                        >
                            Editar
                        </Button>
                        <Button
                            variant="destructive"
                            @click="deleteItem(item.id, item.name)"
                            class="cursor-pointer"
                        >
                            Borrar
                        </Button>
                    </div>
                </template>
            </CustomTable>
        </div>
    </AppLayout>
</template>
