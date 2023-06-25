import {router} from "@inertiajs/vue3";
import {reactive} from "vue";
import {watchDebounced} from "@vueuse/core";

export default function useList() {
    const {search, sort} = route().params;

    const data = reactive({search, sort})

    watchDebounced(data, () => {
            router.reload({data})
        },
        {debounce: 200, maxWait: 500},
    )

    const handleSortChange = ({order, prop}) => {
        data.sort = (order === 'ascending' ? '' : '-') + prop
        router.reload({data})
    }

    return {
        query: data,
        handleSortChange
    };
}
