<script lang="ts" setup>
import {useForm,Link} from "@inertiajs/vue3"
import Modal from "../Layouts/Modal.vue"
import {useModal} from "momentum-modal";
import {onMounted, ref} from "vue";
import User from "@/Components/User.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    project: {
        type: Object,
    },
    clients: {
        type: Array,
    },
    users: {
        type: Array,
    }
});
const form = useForm(Object.assign({
    name: '',
    description: null,
    client_id: null,
    members: []
}, props.project))

const {close, redirect} = useModal()

const url = route(`projects.${props.project ? 'edit' : 'create'}`, {project: props.project?.id});

const create = () => form.post(url, {
    onSuccess: () => {
        redirect()
    }
});

onMounted(() => {
    setTimeout(() => {
        document.querySelector('.focus-me input').focus();
    }, 100)
})

const activeTab = ref('general');
</script>

<template>
    <Modal>
        <template #title>{{ project ? 'Edit project' : 'Create a new project'}}</template>

        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <el-form label-width="120px">
                    <el-form-item label="Name" :class="{'is-error':true}">
                        <el-input v-model="form.name" class="focus-me" />
                        <InputError :message="form.errors.name" />
                    </el-form-item>
                    <el-form-item label="Description">
                        <el-input v-model="form.description" type="textarea" autosize />
                    </el-form-item>
                    <el-form-item label="Client">
                        <el-select v-model="form.client_id"  placeholder="Select">
                            <el-option
                                v-for="item in clients"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value"
                            ></el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="Members">
                        <el-select v-model="form.members"
                                   value-key="id"
                                   filterable multiple
                                   placeholder="Select"
                                   style="width: 100%;"
                                   fit-input-width>
                            <el-option
                                v-for="item in users"
                                :key="item.id"
                                :label="item.full_name"
                                :value="item"
                            >
                                <User :user="item" disable-popover />
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
            <el-tab-pane label="Custom fields" name="fields">Custom fields</el-tab-pane>
        </el-tabs>

        <template #footer>
      <span class="dialog-footer">
        <el-button @click="close">Cancel</el-button>

          <el-button type="primary" @click="create">
          {{ project ? 'Save' : 'Create'}}
        </el-button>
      </span>
        </template>
    </Modal>
</template>
