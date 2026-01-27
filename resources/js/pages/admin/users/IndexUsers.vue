<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import CustomTable from '@/components/CustomTable.vue';
import SearchInput from '@/components/SearchInput.vue';
import Button from '@/components/ui/button/Button.vue';
import { useTableActions } from '@/composables/actions/useTableActions';
import { useFlashMessages } from '@/composables/useFlashMessages';
import { USER_COLUMNS } from '@/config/userColumns';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

useFlashMessages();

const props = defineProps<{
    users: object;
    filters: { search: string };
}>();

const search = ref(props.filters.search || '');

watch(search, (value) => {
    router.get('/users', { search: value }, { preserveState: true });
});

const { deleteItem, editItem } = useTableActions('users');

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Usuarios', href: '/users' }];
</script>

<template>
    <Head title="Usuarios" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <SearchInput route-name="/users" :initial-value="filters.search" />
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
