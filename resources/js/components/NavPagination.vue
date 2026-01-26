<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
});
</script>

<template>
    <nav
        v-if="links.length > 3"
        class="mt-4 flex items-center justify-center space-x-1"
    >
        <template v-for="(link, key) in links" :key="key">
            <div
                v-if="link.url === null"
                class="rounded-md border px-3 py-2 text-sm text-muted-foreground opacity-50"
                v-html="link.label"
            />

            <Link
                v-else
                :href="link.url"
                class="rounded-md border px-3 py-2 text-sm transition-colors"
                :class="{
                    'bg-primary font-bold text-primary-foreground': link.active,
                    'hover:bg-accent hover:text-accent-foreground':
                        !link.active,
                }"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
