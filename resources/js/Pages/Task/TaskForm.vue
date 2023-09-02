<script setup>
import {useForm, usePage} from "@inertiajs/vue3"
import Modal from "../../Layouts/Modal.vue"
import {useModal} from "momentum-modal";
import {inject, onMounted} from "vue";
import TaskForm from "@/Components/Task/TaskForm.vue";

const props = defineProps({
    project: {
        type: Object,
        required: true
    },
    priorities: {
        type: Array,
        required: true
    },
    statuses: {
        type: Array,
        required: true
    },
    users: {
        type: Array,
        required: true
    },
    custom_fields: {
        type: Array,
        required: false,
        default: () => []
    }
});

const form = useForm({
    subject: null,
    description: null,
    assignees: null,
    private: null,
    status_id: props.statuses[0].id,
    priority_id: props.priorities[Math.floor(props.priorities.length / 2)].id,
    tags: null,
    custom_fields: props.custom_fields || {},
})

const handleTasks = inject('handleTasks', () => {
});

const {close, redirect, show} = useModal()
console.log(123,usePage().props);
const url = route('project.tasks.create', {project: usePage().props.project.id});

const create = (open = 0) => form.transform((data) => ({
    task: form.data(),
})).post(url + '?open=' + open, {
    preserveState: true,
    preserveScroll: true,
    only: ['projects', 'tasks', 'errors'],
    onSuccess: (response) => {
        close();
    }
});

const createAndOpen = () => create(1)

onMounted(() => {
    setTimeout(() => {
        document.querySelector('.focus-me input')?.focus();
    }, 100)
})
</script>
<template>
    <Modal>
        <template #title>Create a new task</template>

        <el-form label-width="120px" label-position="top">
            <TaskForm :priorities="priorities"
                      :statuses="statuses"
                      :users="users"
                      v-model="form"/>
        </el-form>

        <template #footer>
      <span class="dialog-footer">
        <el-button @click="close">Cancel</el-button>

        <el-button type="success" @click="createAndOpen">
                <i class="fa-solid fa-circle-plus mr-2"></i>
          Create and open
        </el-button>
          <el-button type="success" @click="create(0)">
              <i class="fa-solid fa-circle-plus mr-2"></i>
          Create
        </el-button>
      </span>
        </template>
    </Modal>
</template>
