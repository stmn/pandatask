<script setup>
import {Link, router} from "@inertiajs/vue3";
import {ElMessage} from "element-plus";

const props = defineProps([
    'imageUrl',
    'uploadUrl',
    'deleteUrl',
]);

const emit = defineEmits(['uploaded']);

const handleSuccess = (response, uploadFile) => {
    emit('uploaded', URL.createObjectURL(uploadFile.raw));
    router.reload({only: ['settings']});
}

const handleError = (err) => {
    ElMessage.error({
        message: JSON.parse(err.message).message,
        duration: 5000,
    });
}
</script>
<template>
    <Link v-if="imageUrl"
          :href="deleteUrl"
          method="post"
          preserve-scroll>
        <el-button size="small" type="danger" plain mb-2>
            <i class="fas fa-trash-alt mr-2"></i> Remove
        </el-button>
    </Link>

    <el-upload
        :action="uploadUrl"
        list-type="picture"
        :show-file-list="false"
        :on-success="handleSuccess"
        :on-error="handleError"
        accept="image/png"
        :headers="{'X-CSRF-TOKEN': $page.props.csrf_token, 'Accept': 'application/json'}"
    >
        <img v-if="imageUrl"
             :src="`${imageUrl.includes('blob:http')?imageUrl:'/storage/'+imageUrl}`"/>

        <el-button v-else size="small" type="primary" plain>
            <i class="fas fa-upload mr-2"></i> Upload
        </el-button>
    </el-upload>
</template>
