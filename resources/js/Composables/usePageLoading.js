import {router} from "@inertiajs/vue3";
import {reactive, ref} from "vue";
import {watchDebounced} from "@vueuse/core";

const loading = ref(false);

export default function usePageLoading() {
    return {
        loading,
    };
}
