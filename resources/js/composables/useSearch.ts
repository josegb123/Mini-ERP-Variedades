// resources/js/composables/useSearch.ts
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { ref, watch } from 'vue';

export function useSearch(routeName: string, initialValue: string = '') {
    const search = ref(initialValue);

    // Encapsulamos la lógica de navegación
    const navigate = debounce((value: string) => {
        router.get(
            routeName,
            { search: value },
            {
                preserveState: true,
                replace: true,
                preserveScroll: true,
            },
        );
    }, 400);

    // Vigilamos los cambios
    watch(search, (newValue) => {
        navigate(newValue);
    });

    return {
        search,
    };
}
