import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { toast } from 'vue-sonner';

export function useFlashMessages() {
    const page = usePage();

    watch(
        () => page.flash,
        (flash: any) => {
            if (!flash?.message) return;

            const options = {
                success: () => toast.success(flash.message),
                error: () => toast.error(flash.message),
                fail: () => toast.error(flash.message),
                default: () => toast(flash.message),
            };

            const strategy =
                options[flash.status as keyof typeof options] ||
                options.default;
            strategy();
        },
        { deep: true, immediate: true },
    );
}
