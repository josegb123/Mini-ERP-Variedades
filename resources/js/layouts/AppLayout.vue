<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import 'vue-sonner/style.css';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
    <Toaster
        :toast-options="{
            style: {
                background: '#1f2937',
                color: '#f9fafb',
                border: '1px solid #374151',
            },
            class: 'my-custom-toast',
        }"
    />
</template>
