<script setup>
const props = defineProps({
    media: {
        type: Array,
        required: false
    },
})
</script>

<template>
    <ul class="el-upload-list el-upload-list--text">
        <li class="el-upload-list__item is-success" tabindex="0" v-for="file in media">
            <div class="el-upload-list__item-info"><a :href="file.original_url" target="_blank"
                                                      class="el-upload-list__item-name">
                <i class="el-icon el-icon--document">
                    <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor"
                              d="M832 384H576V128H192v768h640V384zm-26.496-64L640 154.496V320h165.504zM160 64h480l256 256v608a32 32 0 0 1-32 32H160a32 32 0 0 1-32-32V96a32 32 0 0 1 32-32zm160 448h384v64H320v-64zm0-192h160v64H320v-64zm0 384h384v64H320v-64z"></path>
                    </svg>
                </i><span class="el-upload-list__item-file-name">{{ file.file_name }}</span></a>
            </div>
        </li>
    </ul>
</template>
