import {router} from "@inertiajs/vue3";
import {reactive} from "vue";
import {watchDebounced} from "@vueuse/core";

export default function useList({only} = {only: []}) {
    const {search, sort} = route().params;

    const data = reactive({search, sort})

    watchDebounced(data, () => {
            router.reload({data, only, preserveState: true})
        },
        {debounce: 200, maxWait: 500},
    )

    const handleSortChange = ({order, prop}) => {
        data.sort = (order === 'ascending' ? '' : '-') + prop
        router.reload({data, only, preserveState: true})
    }

    return {
        query: data,
        handleSortChange
    };
}
