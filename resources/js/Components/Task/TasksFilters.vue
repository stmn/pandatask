<template>
    <template v-for="tag in tags">
        <el-tag closable mr-2 @close="tag.handleClose">{{ tag.name }}</el-tag>
    </template>

    <br><br>
    <el-config-provider size="default">
    <el-popover trigger="hover" :width="240">
        <template #default>
            <el-collapse accordion>
                <el-collapse-item v-for="filter in filters" :name="filter.name">
                    <template #title>
                        {{ filter.name }}
                    </template>

                    <el-card shadow="never">
                        <div v-for="option in filter.options">
                            <el-checkbox-group v-model="filtersValues[filter.name.toLowerCase()]">
                                <el-checkbox :label="option.value">{{ option.name }}</el-checkbox>
                            </el-checkbox-group>
                        </div>
                    </el-card>
                </el-collapse-item>
            </el-collapse>
        </template>
        <template #reference>
            <span>Filters <i class="fa-solid fa-chevron-down"></i></span>
        </template>
    </el-popover>
    </el-config-provider>
</template>

<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import {computed, ref, watch} from "vue";
import URI from 'urijs';

const filters = [
    {
        'name': 'Status',
        'options': [
            {
                'name': 'Not started',
                'value': 1
            },
            {
                'name': 'In Progress',
                'value': 2
            },
            {
                'name': 'On Hold',
                'value': 3
            },
        ],
    },
    {
        'name': 'Priority',
        'options': [
            {
                'name': 'Low',
                'value': 1
            },
            {
                'name': 'Medium',
                'value': 2
            },
            {
                'name': 'High',
                'value': 3
            },
        ],
    }
]

const filtersValues = ref({
    // get status from query string
    status: URI(location.href).search(true).status?.split(',').map(Number) ?? [],
    priority: URI(location.href).search(true).priority?.split(',').map(Number) ?? []
});

const tags = computed(() => {
    const tags = [];

    for (const filter of filters) {
        for (const option of filter.options) {
            if (filtersValues.value[filter.name.toLowerCase()].includes(option.value)) {
                tags.push({
                    name: option.name,
                    handleClose() {
                        filtersValues.value[filter.name.toLowerCase()] = filtersValues.value[filter.name.toLowerCase()].filter((value) => value !== option.value);
                    }
                });
            }
        }
    }

    return tags;
});

watch(filtersValues.value, (value) => {
    console.log('filtersValues', filtersValues.value)

    let url = new URL(location.href)

    const data = {}
    for(const filter of filters) {
        const key = filter.name.toLowerCase();
        data[key] = undefined;

        const value = filtersValues.value[key];
        if(value.length > 0) {
            data[key] = value;
        }

        url.searchParams.delete(key);
    }


    for (const key in data) {
        if (data[key] !== undefined) {
            url.searchParams.set(key, data[key]);
        }
    }

    router.replace(url.toString().replaceAll('%2C', ','), {
        method: 'get',
        only: ['tasks'],
        // data: url
        replace: false,
        preserveState: true,
        preserveScroll: true,
    });
});
</script>

<style lang="scss">
.el-popper {
    padding: 5px 15px 5px 15px !important;
}
.el-collapse {
    border: 0;
    .el-collapse-item__header {
        border: 0;
        --el-collapse-header-height: 40px;
    }
    // last header

    .el-collapse-item__wrap {
        border: 0;
    }
    .el-collapse-item__content {
        margin: 0;
        padding: 0;
    }

    .el-card {
        background: var(--el-border-color-extra-light);
        .el-card__body {
            padding: 10px 15px;
        }
    }
}
</style>
