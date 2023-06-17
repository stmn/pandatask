<script lang="ts" setup>
import {useForm} from "@inertiajs/vue3"
import Modal from "../Layouts/Modal.vue"
import {useModal} from "momentum-modal";
import {onMounted, ref} from "vue";
import User from "@/Components/User.vue";
import InputError from "@/Components/InputError.vue";
import {useNow} from "@vueuse/core";

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
    start_at: null,
    end_at: null,
    task: {},
}, props.time))

const {close, redirect} = useModal()

const url = route(`project.timesheets.${props.time ? 'edit' : 'create'}`, {project: props.project?.id, time: props.time?.id});

const create = () => form.post(url, {
    onSuccess: () => {
        redirect()
    }
});

onMounted(() => {
    setTimeout(() => {
        // document.querySelector('.focus-me input').focus();
    }, 100)
})

const activeTab = ref('general');

const defaultTime = new Date()
</script>

<template>
    <Modal>
        <template #title>{{ time ? 'Edit time' : 'Create a new time' }}</template>

        {{ JSON.stringify(form) }}

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
                <InputError :message="form.errors.task" />
            </el-form-item>
            <el-form-item label="Start">
                <el-date-picker
                    v-model="form.start_at"
                    type="datetime"
                    :default-time="defaultTime"
                />
                <InputError :message="form.errors.start_at" />
            </el-form-item>
            <el-form-item label="End">
                <el-date-picker
                    v-model="form.end_at"
                    type="datetime"
                    :default-time="defaultTime"
                />
                <InputError :message="form.errors.end_at" />
            </el-form-item>
            <el-form-item label="Comment">
                <el-input v-model="form.comment" type="textarea"/>
                <InputError :message="form.errors.comment" />
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
