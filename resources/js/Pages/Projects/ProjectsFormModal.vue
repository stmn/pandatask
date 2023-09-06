<script setup>
import {useForm, usePage} from "@inertiajs/vue3"
import Modal from "../../Layouts/Modal.vue"
import {useModal} from "momentum-modal";
import {onMounted, ref} from "vue";
import InputError from "@/Components/Forms/InputError.vue";
import UserName from "@/Components/User/UserName.vue";
import UserAvatar from "@/Components/User/UserAvatar.vue";

const props = defineProps({
    project: {
        type: Object,
    },
    clients: {
        type: Array,
    },
    team_members: {
        type: Array,
    }
});

// if(!props.custom_fields){
props.custom_fields = [];
// }

const form = useForm(Object.assign({
    id: null,
    name: '',
    description: null,
    clients: [],
    team_members: [],
    statuses: [],
    priorities: [],
    custom_fields: [],
    delete_project: false,
    delete_project_name: '',
}, props.project))

if (!props.project) {
    form.statuses = usePage().props.statuses.map((status) => status.id);
    form.priorities = usePage().props.priorities.map((status) => status.id);
}

const {close, redirect} = useModal()

const url = route(`projects.${props.project ? 'edit' : 'create'}`, {project: props.project?.id});

const create = () => form.post(url, {
    preserveState: true,
    preserveScroll: true,
    only: ['tasks', 'projects', 'errors', 'flash'],
    onSuccess: () => {
        close();
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
        <template #title>{{ project ? 'Project settings' : 'Create a new project' }}</template>

        <el-tabs v-model="activeTab" type="border-card">
            <el-tab-pane name="general">
                <template #label>
                    <i class="fa-solid fa-home mr-2"></i> General
                </template>
                <el-form label-width="120px">
                    <el-form-item label="Name" :class="{'is-error':form.errors.name}">
                        <el-input v-model="form.name" class="focus-me"/>
                        <InputError :message="form.errors.name"/>
                    </el-form-item>
                    <el-form-item label="Description">
                        <el-input v-model="form.description" type="textarea" autosize/>
                    </el-form-item>

                    <el-form-item label="Client users">
                        <el-select v-model="form.clients"
                                   value-key="id"
                                   filterable multiple
                                   placeholder="Select"
                                   style="width: 100%;"
                                   fit-input-width>
                            <template #prefix>
                                <i class="fa-solid fa-users"></i>
                            </template>
                            <el-option
                                v-for="item in clients"
                                :key="item.id"
                                :label="item.full_name"
                                :value="item"
                            >
                                <div flex items-center>
                                    <UserAvatar :user="item"/>
                                    <UserName :user="item"/>
                                </div>
                            </el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="Team members">
                        <el-select v-model="form.team_members"
                                   value-key="id"
                                   filterable multiple
                                   placeholder="Select"
                                   style="width: 100%;"
                                   fit-input-width>
                            <template #prefix>
                                <i class="fa-solid fa-users"></i>
                            </template>
                            <el-option
                                v-for="item in team_members"
                                :key="item.id"
                                :label="item.full_name"
                                :value="item"
                            >
                                <div flex items-center>
                                    <UserAvatar :user="item"/>
                                    <UserName :user="item"/>
                                </div>
                            </el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="Statuses">
                        <el-checkbox-group v-model="form.statuses">
                            <el-checkbox-button v-for="status in $page.props.statuses"
                                                :key="status.id"
                                                :label="status.id">
                                {{ status.name }}
                            </el-checkbox-button>
                        </el-checkbox-group>
                    </el-form-item>

                    <el-form-item label="Priorities">
                        <el-checkbox-group v-model="form.priorities">
                            <el-checkbox-button v-for="priority in $page.props.priorities"
                                                :key="priority.id"
                                                :label="priority.id">
                                {{ priority.name }}
                            </el-checkbox-button>
                        </el-checkbox-group>
                    </el-form-item>

                  <el-form-item v-if="form.id">
                    <el-checkbox v-model="form.delete_project" mr-2>
                      <el-text type="danger">Delete project</el-text>
                    </el-checkbox>
                    <el-input v-if="form.delete_project"
                              type="text"
                              placeholder="Enter project name to confirm"
                              v-model="form.delete_project_name" />
                    <InputError :message="form.errors.delete_project_name"/>
                  </el-form-item>
                </el-form>
            </el-tab-pane>
            <el-tab-pane name="fields">
                <template #label>
                    <i class="fa-solid fa-table-list mr-2"></i> Custom fields
                    <el-tooltip effect="dark">
                        <template #content>
                            Custom fields are used to store additional informations about tasks.
                        </template>
                        <i class="fa-solid fa-info-circle ml-2"></i>
                    </el-tooltip>
                </template>

                <el-button type="primary"
                           mb-2
                           @click="form.custom_fields.push({key: '', label: '', type: 'text'})">
                    <i class="fa-solid fa-circle-plus mr-2"></i>
                    Add field
                </el-button>

                <!--                {{ JSON.stringify(form.errors) }}-->

                <el-table :data="form.custom_fields" w-full>
                    <el-table-column prop="key" width="130">
                        <template #header>
                            <el-tooltip effect="dark">
                                <template #content>
                                    <p m-0>Unique key is used to identify the field in the database.</p>
                                    <p m-0><b>Can't be changed after creation.</b></p>
                                </template>
                                <i class="fa-solid fa-info-circle mr-1"></i>
                            </el-tooltip>
                            Unique key
                        </template>
                        <template #default="{row}">
                            <el-input v-model="row.key" :disabled="row?.id"/>
                            <InputError
                                :message="form.errors['custom_fields.' + form.custom_fields.indexOf(row) + '.key']"/>
                        </template>
                    </el-table-column>
                    <el-table-column prop="label" label="Label">
                        <template #default="{row}">
                            <el-input v-model="row.label"/>
                            <InputError
                                :message="form.errors['custom_fields.' + form.custom_fields.indexOf(row) + '.label']"/>
                        </template>
                    </el-table-column>
                    <el-table-column prop="type" label="Type" width="120">
                        <template #default="{row}">
                            <el-select v-model="row.type" placeholder="Select" style="width: 100%;">
                                <el-option label="Text" value="text"></el-option>
                                <el-option label="Number" value="number"></el-option>
                                <el-option label="Date" value="date"></el-option>
                                <el-option label="Select" value="select"></el-option>
                                <el-option label="Boolean" value="boolean"></el-option>
                            </el-select>
                            <InputError
                                :message="form.errors['custom_fields.' + form.custom_fields.indexOf(row) + '.type']"/>
                        </template>
                    </el-table-column>
                    <el-table-column prop="options" label="Options" width="280">
                        <template #default="{row}">
                            <el-select v-model="row.options"
                                       :disabled="row.type !== 'select'"
                                       multiple
                                       filterable
                                       allow-create
                                       collapse-tags
                                       :max-collapse-tags="1"
                                       :placeholder="row.type !== 'select' ? 'Not available' : ''"
                                       style="width: 100%;">

                            </el-select>
                            <InputError
                                :message="form.errors['custom_fields.' + form.custom_fields.indexOf(row) + '.options']"/>
                        </template>
                    </el-table-column>
                    <el-table-column width="50">
                        <template #default="{row}">
                            <el-button type="danger" plain circle
                                       size="small"
                                       @click="form.custom_fields.splice(form.custom_fields.indexOf(row), 1)">
                                <i class="fa-solid fa-times"></i>
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-tab-pane>
        </el-tabs>

        <template #footer>
      <span class="dialog-footer">
        <el-button @click="close">Cancel</el-button>

          <el-button type="success" @click="create">
              <i class="fa fa-circle-plus mr-2"></i>
          {{ project ? 'Save' : 'Create' }}
        </el-button>
      </span>
        </template>
    </Modal>
</template>
