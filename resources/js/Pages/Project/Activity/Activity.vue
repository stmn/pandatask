<script setup>
import {Head} from '@inertiajs/vue3';
import {ref} from "vue";
import ProjectLayout from "~/js/Layouts/ProjectLayout.vue";
import ActivityList from "@/Components/Activity/ActivityList.vue";
import Layout from "~/js/Layouts/Layout.vue";
import {Portal} from "@headlessui/vue";

defineOptions({layout: [Layout, ProjectLayout]})

const props = defineProps({
    activeTab: String,
    project: Object,
    activities: Object,
});

const activeTab = ref(props.activeTab);
</script>

<!--<script>-->
<!--import ProjectLayout from "~/js/Layouts/ProjectLayout.vue";-->
<!--export default { layout: ProjectLayout };-->
<!--</script>-->

<!--<script>-->
<!--import ProjectLayout from "~/js/Layouts/ProjectLayout.vue";-->

<!--export default {-->
<!--    // Using a render function...-->
<!--    layout: (h, page) => {-->
<!--        return h(ProjectLayout, () => page);-->
<!--    },-->

<!--    // Using the shorthand...-->
<!--    // layout: [ProjectLayout],-->
<!--}-->
<!--</script>-->

<template>
    <Head :title="project.name + ' - Activities'"/>

    <!--    <template v-slot:header>-->
    <!--        test-->
    <!--    </template>-->
    <!--        <Portal target="#test">-->
    <!--            test-->
    <!--        </Portal>-->

    <!--    <ProjectLayout :project="project" :active-tab="activeTab">-->
    <ActivityList :activities="activities"
                  mt-2
                  show-details
                  show-task/>
    <!--    </ProjectLayout>-->
</template>
