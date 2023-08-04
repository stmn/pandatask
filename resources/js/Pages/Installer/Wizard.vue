<template>
    <Head title="Your page title" />

    <div id="installer" py-10 h-full>
        <el-container style=" width: 100%; max-width: 900px; margin: auto;">
            <div style="display: flex; flex-direction: column; width: 100%;">
                <el-main
                    style="
            background: var(--el-bg-color);
            color: var(--el-text-color-primary);
            border-radius: 15px;
            margin: 0 20px;
            position: relative;
            overflow: hidden;">
                    <!--                    <h1 class="logo m-0 mb-10" style="color: var(&#45;&#45;brand-text-color); text-align: center;">-->
                    <!--                        <Link href="/" preserve-state style="color: var(&#45;&#45;el-text-color);">-->
                    <!--                            <Logo style="height:100px;display: inline-block;"/>-->
                    <!--                            <br><span :style="`font-weight: 400;`"><b>PANDA</b>TASK</span>-->
                    <!--                        </Link>-->
                    <!--                    </h1>-->

                    <el-steps :active="step" finish-status="success" align-center mb-5 mt-2>
                        <el-step title="Requirements"/>
                        <el-step title="Permissions"/>
                        <el-step title="Database"/>
                        <el-step title="Account"/>
                        <el-step title="Environment"/>
                        <el-step title="Finish"/>
                    </el-steps>

                    <div v-loading="loading">

                        <div v-if="step === 0">
                            <Transition appear>
                                <div>
                                    <el-alert type="info" :closable="false" show-icon style="margin-bottom: 10px;">
                                        Please make sure the following requirements are met.
                                    </el-alert>

                                    <el-card v-for="(req,key) in requirments" mb-3 shadow="never">
                                        <div flex items-center justify-between>
                                            <div>
                                                <el-text :type="req.ok?'default':'error'">
                                                    <i class="fa-solid fa-arrow-right mr-2"></i>
                                                </el-text>
                                                <el-text :type="req.ok?'default':'error'">
                                                    <span>{{ req.name }}</span>
                                                </el-text>
                                            </div>
                                            <el-text :type="req.ok?'success':'error'">
                                                <i v-if="loaded > key"
                                                   :class="`fa-fw fa-xl ${req.ok?'fas fa-check':'fas fa-xmark'}`"></i>
                                                <i v-else class="fas fa-spinner fa-spin"/>
                                            </el-text>
                                        </div>
                                    </el-card>

                                    <el-alert v-if="!requirmentsPassed" type="error" :closable="false" show-icon>
                                        You need to pass all requirements to continue.
                                    </el-alert>
                                </div>
                            </Transition>
                        </div>

                        <div v-else-if="step === 1">
                            <el-alert type="info" :closable="false" show-icon style="margin-bottom: 10px;">
                                Please make sure the following folders and files are writable.
                            </el-alert>

                            <el-card v-for="(req,key) in permissions" mb-3 shadow="never">
                                <div flex items-center justify-between>
                                    <el-text>
                                        <i class="fas fa-folder mr-2"></i>
                                        {{ req.folder }}
                                    </el-text>
                                    <el-text :type="req.writable?'success':'error'">
                                    <span v-if="loaded > key">
                                        <i :class="`fas fa-lg ${req.writable?'fa-check text-success':'fa-xcross text-danger'}`"></i>
                                        <b ml-2>{{ req.permission }}</b>
                                    </span>
                                        <i v-else class="fas fa-spinner fa-spin"/>
                                    </el-text>
                                </div>
                            </el-card>

                            <el-alert v-if="!permissionsPassed" type="error" :closable="false" show-icon>
                                You need to pass all permissions to continue.
                            </el-alert>
                        </div>

                        <div v-else-if="step === 2">
                            <el-form label-position="top">
                                <el-alert v-if="true" type="info" :closable="false" show-icon
                                          style="margin-bottom: 15px;">
                                    Provide your MySQL database credentials.
                                </el-alert>

                                <el-row :gutter="20">
                                    <el-col :span="12">
                                        <el-form-item label="Database host" prop="host">
                                            <el-input v-model="db.host">
                                                <template #prefix>
                                                    <i class="fas fa-server"/>
                                                </template>
                                            </el-input>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="Database port" prop="port">
                                            <el-input v-model="db.port">
                                                <template #prefix>
                                                    <i class="fas fa-server"/>
                                                </template>
                                            </el-input>
                                        </el-form-item>
                                    </el-col>
                                </el-row>

                                <el-form-item label="Database name" prop="database">
                                    <el-input v-model="db.database">
                                        <template #prefix>
                                            <i class="fas fa-database"/>
                                        </template>
                                    </el-input>
                                </el-form-item>

                                <el-row :gutter="20">
                                    <el-col :span="12">
                                        <el-form-item label="Database username" prop="username">
                                            <el-input v-model="db.username">
                                                <template #prefix>
                                                    <i class="fas fa-user"/>
                                                </template>
                                            </el-input>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="Database password" prop="password">
                                            <el-input type="password" v-model="db.password">
                                                <template #prefix>
                                                    <i class="fas fa-lock"/>
                                                </template>
                                            </el-input>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </el-form>
                        </div>

                        <div v-else-if="step === 3">
                            <el-form label-position="top">
                                <el-alert v-if="true" type="info" :closable="false" show-icon
                                          style="margin-bottom: 15px;">
                                    Provide your administrator account credentials.
                                </el-alert>

                                <el-row :gutter="20">
                                    <el-col :span="12">
                                        <el-form-item label="Name" prop="name">
                                            <el-input v-model="account.name">
                                                <template #prefix>
                                                    <i class="fas fa-user"/>
                                                </template>
                                            </el-input>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="Email" prop="email">
                                            <el-input v-model="account.email">
                                                <template #prefix>
                                                    <i class="fas fa-envelope"/>
                                                </template>
                                            </el-input>
                                        </el-form-item>
                                    </el-col>
                                </el-row>

                                <el-row :gutter="20">
                                    <el-col :span="12">
                                        <el-form-item label="Password" prop="password">
                                            <el-input type="password" v-model="account.password">
                                                <template #prefix>
                                                    <i class="fas fa-lock"/>
                                                </template>
                                            </el-input>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="Password confirmation" prop="password_confirmation">
                                            <el-input type="password" v-model="account.password_confirmation">
                                                <template #prefix>
                                                    <i class="fas fa-lock"/>
                                                </template>
                                            </el-input>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </el-form>
                        </div>

                        <div v-else-if="step === 4">
                            <el-alert v-if="true" type="info" :closable="false" show-icon
                                      style="margin-bottom: 15px;">
                                Please review your environment settings.
                            </el-alert>
                            <el-input v-model="env" type="textarea" :rows="15"></el-input>
                        </div>

                        <div v-else-if="step === 5">
                            <div text-center py-20>
                                <el-text size="large" type="success">
                                    <i class="fa-regular fa-thumbs-up fa-beat" mb-10
                                       style="font-size: 100px;"></i>
                                    <p>
                                        <b>Everything looks good!</b>
                                    </p>
                                </el-text>
                                <el-text size="large">
                                    <p>Click <b>Login</b> to continue.</p>
                                </el-text>
                            </div>
                        </div>
                    </div>

                    <div>
                        <el-button type="danger" style="margin-top: 12px"
                                   v-if="step > 0"
                                   float-left
                                   :loading="loading"
                                   :disabled="loading"
                                   size="large"
                                   @click="back">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </el-button>
                        <el-button type="primary"
                                   style="margin-top: 12px"
                                   float-right
                                   :loading="loading"
                                   :disabled="nextDisabled"
                                   size="large"
                                   @click="next">
                            {{ steps[step].buttonText || 'Next step' }}
                            <i class="fas fa-arrow-right ml-2"></i>
                        </el-button>
                    </div>
                </el-main>
            </div>
        </el-container>
    </div>
</template>
<script setup>
import {useDark, useStorage} from "@vueuse/core";
import {computed, ref} from "vue";
import {ElMessage} from "element-plus";

useDark();

const props = defineProps({
    requirments: Array,
    permissions: Array,
    env: String,
    app_key: String,
})

console.log(55, props.app_key)

const requirments = ref(props.requirments);
const permissions = ref(props.permissions);
const env = ref('');

const requirmentsPassed = computed(() => {
    return requirments.value.every(req => req.ok);
})

const permissionsPassed = computed(() => {
    return permissions.value.every(req => req.writable);
})

const nextDisabled = computed(() => {
    if (step.value === 0) return !requirmentsPassed.value;
    if (step.value === 1) return !permissionsPassed.value;
    if (step.value === 2) return !db.value.host || !db.value.port || !db.value.database || !db.value.username;
    if (step.value === 3) return !account.value.name || !account.value.email || !account.value.password || !account.value.password_confirmation;
    if (step.value === 4) return false;
    if (step.value === 5) return false;
})

const loaded = ref(0);
setInterval(() => {
    loaded.value++;
}, 250);

const db = useStorage('installer_db', {
    host: '127.0.0.1',
    port: '3306',
    database: '',
    username: '',
    password: '',
})

const account = useStorage('installer_account', {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const step = useStorage('installer_step', 0);

const loading = ref(false);

const steps = {
    0: {},
    1: {},
    2: {},
    3: {},
    4: {
        onload: () => {
            console.log(11, props.app_key)
            let replacers = {
                'APP_KEY': props.app_key,
                'APP_URL': window.location.origin,
                'APP_ENV': 'production',
                'APP_DEBUG': 'false',
                'DB_HOST': db.value.host,
                'DB_PORT': db.value.port,
                'DB_DATABASE': db.value.database,
                'DB_USERNAME': db.value.username,
                'DB_PASSWORD': db.value.password,
            };

            let _env = props.env;

            // Podmieniamy odpowiednie wartości w pliku .env
            Object.keys(replacers).forEach(key => {
                const regex = new RegExp(`${key}=.*`);
                _env = _env.replace(regex, `${key}=${replacers[key]}`);
            });

            env.value = _env;
        },
        buttonText: 'Finish',
    },
    5: {
        buttonText: 'Login',
        after: () => {
            window.location.href = route('dashboard');
        },
    },
};

steps[step.value].onload?.();

const next = async () => {
    loading.value = true;

    const url = route('installer.step', {step: step.value});

    const {data} = await axios.post(url, {
        db: db.value,
        account: account.value,
        env: env.value,
    }, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    });

    steps[step.value].after?.();

    if(data.success) {
        await new Promise(resolve => setTimeout(resolve, 500));
        loading.value = false;

        if (step.value++ > 4) step.value = 0
        loaded.value = 0;

        if(data.message) {
            ElMessage.success(data.message);
        }

        steps[step.value].onload?.();
    } else {
        ElMessage.error(data.message);
        loading.value = false;
    }
}

const back = async () => {
    if (step.value-- < 0) step.value = 2
    loaded.value = 0;
    steps[step.value].onload?.();
}
</script>

<style lang="scss">
#installer {
    .el-card {

    }
}
</style>
