import {ref} from "vue";

const loading = ref(false);

export default function usePageLoading() {
    return {
        loading,
    };
}
