import {computed, nextTick, ref} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";

const page = usePage();
const props = computed(() => page.props);
const adding = ref(props.value?.page);
const editing = ref(false);
const hash = ref(location.hash.replace('#', ''));

const form = useForm({
    title: props.value?.page?.title ?? '',
    content: props.value?.page?.content ?? '',
});

const cancel = () => {
    editing.value = false;
    adding.value = false;
    const style = document.querySelector('style[data-tiptap-style]');
    if (style) {
        style.remove();
    }
}

const savePage = () => {
    form.post(route('project.pages.save', {
        project: props.value.project.id,
        page: adding.value ? null : props.value.page?.slug_title
    }), {
        preserveScroll: true,
        onSuccess: () => {
            cancel();
        }
    })
}

const deletePage = () => {
    form.delete(route('project.pages.delete', {project: props.value.project.id, page: props.value.page?.slug_title}), {
        preserveScroll: true,
        onSuccess: () => {
            cancel();
        }
    })
}

const addPage = () => {
    adding.value = true;
    editing.value = false;
    form.title = '';
    form.content = '';

    nextTick(() => {
        document.getElementById('title')?.focus();
    })
}

export default function useWiki() {
    return {
        adding,
        editing,
        hash,
        form,
        cancel,
        savePage,
        deletePage,
        addPage,
    }
}
