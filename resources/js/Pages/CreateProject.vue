<script lang="ts" setup>
import {useForm} from "@inertiajs/vue3"
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
        // redirect()
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
        <template #title>{{ project ? 'Edit project' : 'Create a new project' }}</template>

        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <el-form label-width="120px">
                    <el-form-item label="Name" :class="{'is-error':form.errors.name}">
                        <el-input v-model="form.name" class="focus-me"/>
                        <InputError :message="form.errors.name"/>
                    </el-form-item>
                    <el-form-item label="Description">
                        <el-input v-model="form.description" type="textarea" autosize/>
                    </el-form-item>
                    <el-form-item label="Client">
                        <el-select v-model="form.client_id" placeholder="Select">
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
                                <User :user="item" disable-popover/>
                            </el-option>
                        </el-select>
                    </el-form-item>

                    STATUS (tags) - START DATE (date) - DUE DATA (date) - PRIOTIY (tags) - HOURLY RATE (number) - ESTIMATION (number) - BILLABLE (boolean) - TAGS -

                    <el-form-item label="Fields">
                        <el-checkbox>Billable fields</el-checkbox>
                        <el-checkbox>Billable fields</el-checkbox>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
            <el-tab-pane label="Custom fields" name="fields">

                <el-config-provider size="small">
                    <el-card>
                        <el-form label-position="top">
                            <div style="display: flex; width:100%;">
                                <el-form-item label="Key">
                                    <el-input model-value="Field name" placeholder="Add a new field"
                                              suffix-icon="el-icon-plus"/>
                                </el-form-item>
                                <el-form-item label="Type">
                                    <el-select model-value="text">
                                        <el-option label="Text" value="text"></el-option>
                                        <el-option label="Number" value="number"></el-option>
                                        <el-option label="Date" value="date"></el-option>
                                        <el-option label="Select" value="select"></el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="Value">
                                    <el-input model-value="P" placeholder="Add a new field" suffix-icon="el-icon-plus"/>
                                </el-form-item>
                                <el-form-item label="&nbsp;">
                                    <el-button type="danger">Remove</el-button>
                                </el-form-item>
                            </div>
                        </el-form>
                    </el-card>
                </el-config-provider>
                <el-button type="success">Add field</el-button>

            </el-tab-pane>
        </el-tabs>

        <template #footer>
      <span class="dialog-footer">
        <el-button @click="close">Cancel</el-button>

          <el-button type="success" @click="create">
          {{ project ? 'Save' : 'Create' }}
        </el-button>
      </span>
        </template>
    </Modal>
</template>
