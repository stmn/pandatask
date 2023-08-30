<script setup>
import {useForm, usePage} from '@inertiajs/vue3';

const notifications = [
    {
        name: 'task-assigned',
        text: 'When I am assigned to a task.',
    },
    {
        name: 'project-assigned',
        text: 'When I am assigned to a project.',
    },
    {
        name: 'new-task-activity',
        text: 'When there is a new activity (not comment) in a task I am assigned to.',
    },
    {
        name: 'new-task-comment',
        text: 'When there is a new comment in a task I am assigned to.',
    },
    {
        name: 'upcoming-weekly-deadlin',
        text: 'On Monday at 8:00 AM, when upcoming task deadlines are in the current week.',
    },
    {
        name: 'upcoming-daily-deadlin',
        text: 'At 8:00 AM, when upcoming task deadlines are on the current day.',
    },
]

const userNotifications = usePage().props.notifications;

const form = useForm({
    notifications: notifications.map(notification => {
        return {
            ...notification,
            mail: userNotifications?.[notification.name]?.mail || true
        };
    })
});

const submit = () => {
    form.patch(route('profile.update-notifications'), {
        preserveScroll: true
    });
}

const allSelected = (type) => form.notifications.every(notification => notification[type])

const switchAll = (type) => {
    const selected = allSelected(type);
    form.notifications.forEach(notification => {
        notification[type] = selected ? false : true;
    });
}
</script>

<template>
    <section>
        <h3>Notifications</h3>

        <el-card shadow="never">
            <el-form @submit.prevent="submit" label-width="180" label-position="left">
                <el-form-item style="margin-left: -84px;">
                    <table w-full>
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 80px;">
                                Email
                                <el-button class="py-0" type="default" link
                                           size="small"
                                           @click="switchAll('mail')">
                                    {{ allSelected('mail') ? 'Deselect all' : 'Select all' }}
                                </el-button>
                            </th>
                            <th class="text-left"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="notification in form.notifications">
                            <td class="text-center">
                                <el-checkbox v-model="notification.mail"></el-checkbox>
                            </td>
                            <td>
                                <el-text>{{ notification.text }}</el-text>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </el-form-item>
                <el-form-item>
                    <el-button @click="submit" :color="$primaryColor()" :disabled="form.processing">Save</el-button>
                </el-form-item>
            </el-form>
        </el-card>
    </section>
</template>
