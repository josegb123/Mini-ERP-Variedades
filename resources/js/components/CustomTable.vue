<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import NavPagination from './NavPagination.vue';

defineProps({
    columns: Array,
    data: Object, // El objeto paginado de Laravel
});
</script>

<template>
    <div>
        <div class="rounded-md border bg-card text-card-foreground">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead v-for="col in columns" :key="col.key">
                            {{ col.label }}
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="item in data.data" :key="item.id">
                        <TableCell v-for="col in columns" :key="col.key">
                            <slot
                                v-if="col.custom"
                                :name="col.key"
                                :item="item"
                            />
                            <span v-else>{{ item[col.key] }}</span>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div class="mt-4">
            <NavPagination :links="data.links" />
        </div>
    </div>
</template>
