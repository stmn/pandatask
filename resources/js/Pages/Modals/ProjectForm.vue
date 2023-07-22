<script setup>
import {useForm} from "@inertiajs/vue3"
import Modal from "../../Layouts/Modal.vue"
import {useModal} from "momentum-modal";
import {onMounted, ref} from "vue";
import User from "~/js/Components/User/UserAvatar.vue";
import InputError from "~/js/Components/Forms/InputError.vue";

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

const form = useForm(Object.assign({
    name: '',
    description: null,
    clients: [],
    team_members: []
}, props.project))

// console.log(form.teamMembers, props.project?.team_members)

const {close, redirect} = useModal()

const url = route(`projects.${props.project ? 'edit' : 'create'}`, {project: props.project?.id});

const create = () => form.post(url, {
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
        <template #title>{{ project ? 'Edit project' : 'Create a new project' }}</template>

        <el-form label-width="120px">
            <el-form-item label="Name" :class="{'is-error':form.errors.name}">
                <el-input v-model="form.name" class="focus-me"/>
                <InputError :message="form.errors.name"/>
            </el-form-item>
            <el-form-item label="Description">
                <el-input v-model="form.description" type="textarea" autosize/>
            </el-form-item>
            <!--            <el-form-item label="Client">-->
            <!--                <el-select v-model="form.client_id" placeholder="Select">-->
            <!--                    <el-option-->
            <!--                        v-for="item in clients"-->
            <!--                        :key="item.value"-->
            <!--                        :label="item.label"-->
            <!--                        :value="item.value"-->
            <!--                    ></el-option>-->
            <!--                </el-select>-->
            <!--            </el-form-item>-->

            <el-form-item label="Client users">
                <el-select v-model="form.clients"
                           value-key="id"
                           filterable multiple
                           placeholder="Select"
                           style="width: 100%;"
                           fit-input-width>
                    <el-option
                        v-for="item in clients"
                        :key="item.id"
                        :label="item.full_name"
                        :value="item"
                    >
                        <User :user="item" disable-popover/>
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
                    <el-option
                        v-for="item in team_members"
                        :key="item.id"
                        :label="item.full_name"
                        :value="item"
                    >
                        <User :user="item" disable-popover/>
                    </el-option>
                </el-select>
            </el-form-item>
        </el-form>

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
