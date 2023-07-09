<script lang="ts" setup>
import {useForm} from "@inertiajs/vue3"
import Modal from "../Layouts/Modal.vue"
import {useModal} from "momentum-modal";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    project: {
        type: Object,
    },
    time: {
        type: Object,
    },
    tasks: {
        type: Array,
    },
});

const form = useForm(Object.assign({
    comment: null,
    start_at: new Date(),
    end_at: new Date(),
    task: {},
}, props.time))

if (form.end_at === null) {
    form.end_at = new Date()
}

const {close, redirect} = useModal()

const url = route(`project.timesheets.${props.time ? 'edit' : 'create'}`, {
    project: props.project?.id,
    time: props.time?.id
});

const create = () => form.post(url, {
    preserveState: true,
    preserveScroll: true,
    only: ['times', 'errors', 'auth'],
    onSuccess: () => {
        close()
    }
});
</script>

<template>
    <Modal>
        <template #title>{{ time ? 'Edit time' : 'Create a new time' }}</template>

        <el-form label-width="120px">
            <el-form-item label="Task" :class="{'is-error': form.errors.task}">
                <el-select v-model="form.task" value-key="id" filterable placeholder="Select">
                    <el-option
                        v-for="item in tasks"
                        :key="item.id"
                        :label="item.subject"
                        :value="item"
                    ></el-option>
                </el-select>
                <InputError :message="form.errors.task"/>
            </el-form-item>
            <el-form-item label="Start">
                <el-date-picker
                    v-model="form.start_at"
                    type="datetime"
                />
                <InputError :message="form.errors.start_at"/>
            </el-form-item>
            <el-form-item label="End">
                <el-date-picker
                    v-model="form.end_at"
                    type="datetime"
                />
                <InputError :message="form.errors.end_at"/>
            </el-form-item>
            <el-form-item label="Comment">
                <el-input v-model="form.comment" type="textarea" class="focus-me" autosize/>
                <InputError :message="form.errors.comment"/>
            </el-form-item>
        </el-form>
        <template #footer>
      <span class="dialog-footer">
        <el-button @click="close">Cancel</el-button>

          <el-button type="primary" @click="create">
          {{ time ? 'Save' : 'Create' }}
        </el-button>
      </span>
        </template>
    </Modal>
</template>
