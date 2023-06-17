<script lang="ts" setup>
import {useForm} from "@inertiajs/vue3"
import Modal from "../../Layouts/Modal.vue"
import {useModal} from "momentum-modal";
import {onMounted, onUpdated, ref} from "vue";
import {useFocus} from "@vueuse/core";

const props = defineProps({
    project: {
        type: Object,
        required: true
    },
});
const form = useForm({
    subject: '',
    description: null,
    private: false,
    // first_name: props.contact.first_name,
    // last_name: props.contact.last_name,
})

const {close, redirect} = useModal()

const url = route('project.tasks.create', {project: props.project.id});

const create = (open = 0) => form.post(url+'?open='+open, {
    onSuccess: () => {
        redirect()
    }
});

const createAndOpen = () => create(1)

onMounted(() => {
    setTimeout(() => {
        document.querySelector('.focus-me input').focus();
    }, 100)
})
</script>

<template>
    <Modal>
        <template #title>Create a new task</template>

        <el-form label-width="120px">
            <el-form-item label="Subject">
                <el-input v-model="form.subject" class="focus-me" placeholder="Describe shortly your task..." />
            </el-form-item>
            <el-form-item label="Description">
                <el-input v-model="form.description" type="textarea" placeholder="Write description for more informations..." />
            </el-form-item>
            <el-form-item label="Is private">
                <el-switch
                    v-model="form.private"
                    size="small"
                    active-text="Yes"
                    inactive-text="No"
                />
            </el-form-item>
        </el-form>

        <!--        <form class="mt-6" @submit.prevent="update">-->
        <!--            <div class="grid grid-cols-2 gap-x-6 gap-y-8">-->
        <!--                <text-input v-model="form.first_name" :error="form.errors.first_name" label="First name" />-->
        <!--                <text-input v-model="form.last_name" :error="form.errors.last_name" label="Last name" />-->
        <!--            </div>-->
        <!--            <div class="mt-6 flex justify-between">-->
        <!--                <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Contact</loading-button>-->
        <!--            </div>-->
        <!--        </form>-->
        <template #footer>
      <span class="dialog-footer">
        <el-button @click="close">Cancel</el-button>

        <el-button type="primary" @click="createAndOpen">
          Create and open
        </el-button>
          <el-button type="primary" @click="create">
          Create
        </el-button>
      </span>
        </template>
    </Modal>
</template>
