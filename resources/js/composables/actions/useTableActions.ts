import { router } from '@inertiajs/vue3';

export function useTableActions(resourcePrefix: string) {
    const deleteItem = (id: string | number, name?: string) => {
        if (!confirm(`¿Estás seguro de eliminar a ${name || 'este elemento'}?`))
            return;

        router.delete(`/${resourcePrefix}/${id}`, {
            preserveScroll: true,
            onSuccess: () => {
                // Lógica extra si fuera necesaria
            },
        });
    };

    const editItem = (id: string | number) => {
        router.get(`/${resourcePrefix}/${id}/edit`);
    };

    const createItem = () => {
        router.get(`/${resourcePrefix}/create`);
    };

    return {
        deleteItem,
        editItem,
        createItem,
    };
}
