import {usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import {useNow} from "@vueuse/core";

export default function useTaskTimer() {
    const page = usePage()

    const auth = computed(() => page.props.auth)
    const activeTime = computed(() => page.props.auth.user.active_time)

    const time = computed(() => {
        if (!activeTime.value) {
            return 0;
        }

        const now = useNow();

        const seconds = Math.floor((now.value - Date.parse(activeTime.value?.start_at)) / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);

        return `${hours.toString().padStart(2, '0')}:${(minutes % 60).toString().padStart(2, '0')}:${(seconds % 60).toString().padStart(2, '0')}`;
    });

    const seconds = computed(() => {
        if (!activeTime.value) {
            return 0;
        }

        const now = useNow();

        return Math.floor((now.value - Date.parse(activeTime.value?.start_at)) / 1000);
    });

    return {
        auth,
        activeTime,
        time,
        seconds,
    };
}
