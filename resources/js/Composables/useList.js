import {router} from "@inertiajs/vue3";
import {ref, reactive, watch} from "vue";
import {useStorage, watchDebounced} from "@vueuse/core";

let list = reactive({
    only: undefined,
    search: '',
    sort: '',
    order: '',
    key: 'list',
    columns: [],
    selectedColumns: undefined,
});

export function useCreateList(data) {
    console.log('useCreateList',data)

    // Object.keys(updateObj).forEach(key => {

    if(data?.key) {
        data.selectedColumns = useStorage(data.key, data?.selectedColumns || []);
    }

    list = Object.assign(list, data)
    // list = reactive({...list, ...data});

    const routeParams = route().params;
    list.search = routeParams?.search;
    if (routeParams?.sort) {
        list.order = routeParams.sort.startsWith('-') ? 'desc' : 'asc';
    }

    const buildData = () => {
        const data = {};
        if(list.sort) {
            const sortString = (list.order === 'desc' ? '-' : '') + list.sort.value;
            data.sort = sortString;
        }
        data.search = list.search || undefined;

        return data;
    }

    const reload = () => {
        console.log('reload', buildData())
        router.reload({
            data: buildData(),
            only: list.only,
            preserveState: true
        })
    }

    watchDebounced(() => list.search, reload, {debounce: 200, maxWait: 500})
    watchDebounced(() => list.order, reload, {debounce: 100, maxWait: 200})
    watchDebounced(() => list.sort, reload, {debounce: 100, maxWait: 200})

    return useList();
}

export function useList() {
    const changeSort = (sort) => {
        console.log('sort', {sort})
        if (sort && sort.value !== list.sort.value) {
            list.sort = sort;
        }
    }

    const changeOrder = (order) => {
        console.log('order', {order})
        if (order && list.order !== order) {
            list.order = order;
        }
    }

    const updateColumns = (columns) => {
        list.columns = columns;
    }

    return {
        list,
        changeSort,
        changeOrder,
        updateColumns,
    };
}
