<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { Search, X } from 'lucide-vue-next'; // Agregamos íconos
import { ref, watch } from 'vue';

const props = defineProps<{
    routeName: string;
    initialValue?: string;
}>();

const search = ref(props.initialValue || '');

const searchMethod = debounce((value: string) => {
    router.get(
        props.routeName,
        { search: value },
        { preserveState: true, replace: true, preserveScroll: true },
    );
}, 400);

watch(search, (newValue) => {
    searchMethod(newValue);
});

const clearSearch = () => {
    search.value = '';
};
</script>

<template>
    <div class="relative w-full max-w-md">
        <Search
            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
        />

        <input
            v-model="search"
            type="text"
            placeholder="Buscar usuarios..."
            class="h-10 w-full rounded-md border bg-background pr-10 pl-10 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
        />

        <button
            v-if="search"
            @click="clearSearch"
            class="absolute top-1/2 right-3 -translate-y-1/2 text-muted-foreground hover:text-foreground"
        >
            <X class="h-4 w-4" />
        </button>
    </div>
</template>
