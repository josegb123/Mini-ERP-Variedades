import { ref, watch } from 'vue';

// TODO: Implementar llamada a bd para guardar y actualizar el nombre
const companyName = ref(
    localStorage.getItem('erp_company_name') || 'Mi Empresa ERP',
);

export function useSettings() {
    // Función para actualizar el nombre
    const setCompanyName = (newName: string) => {
        companyName.value = newName;
    };

    // Vigilamos los cambios para persistir en LocalStorage automáticamente
    watch(
        companyName,
        (newValue) => {
            localStorage.setItem('erp_company_name', newValue);
        },
        { immediate: true },
    );

    return {
        companyName,
        setCompanyName,
    };
}
