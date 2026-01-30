<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CustomTable from '@/components/CustomTable.vue';
import SearchInput from '@/components/SearchInput.vue';
import UserDeleteDialog from '@/components/users/UserDeleteDialog.vue';
import UserEditDialog from '@/components/users/UserEditDialog.vue';
import UserFormDialog from '@/components/users/UserFormDialog.vue';
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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Usuarios', href: '/users' }];
</script>

<template>
    <Head title="Usuarios" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex w-full items-center gap-3 py-3">
            <SearchInput v-model="search" />
            <UserFormDialog />
        </div>
        <CustomTable :columns="USER_COLUMNS" :data="users">
            <template #actions="{ item }">
                <div class="flex gap-2">
                    <UserEditDialog :user="item" />
                    <UserDeleteDialog :user="item" />
                </div>
            </template>
        </CustomTable>
    </AppLayout>
</template>
