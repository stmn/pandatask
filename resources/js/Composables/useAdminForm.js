import {useForm} from "@inertiajs/vue3";

export default function useAdminForm({values, singular, plural, onSuccess}) {
    const form = useForm(values);

    const isEdit = form?.id;

    const save = () => {
        const method = isEdit ? 'patch' : 'post';
        const routeName = isEdit ? 'update' : 'store';
        const params = {[singular]: form?.id};
        const url = route(`admin.${plural}.${routeName}`, params);

        form.submit(method, url, {onSuccess});
    };

    return {
        form,
        isEdit,
        save,
    };
}
