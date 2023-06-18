<script setup>
import {Link, usePage} from '@inertiajs/vue3'
import {computed} from "vue";
import Time from "@/Components/Time.vue";
import User from "@/Components/User.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    task: {
        type: Object,
        required: false
    },
    activities: {
        required: true
    },
    showTask: {
        type: Boolean,
        required: false,
        default: false
    }
})

const activitiesData = computed(() => props.activities?.data || props.activities);

const page = usePage()

const auth = computed(() => page.props.auth)
</script>

<template>
    <transition name="el-fade-in-linear">
        <el-timeline style="padding: 5px 0 0 5px;">
            <el-timeline-item v-for="event in activitiesData" class="animated fadeIn" :key="event.id"
                              :id="`activity_${event.id}`">
<!--                {{ JSON.stringify(event) }}-->

                <div style="display: flex; align-items: center;">
                    <User :user="event.user"/>
                    <span style="margin: 0 5px;">{{ event.description }}</span>
                    <Link style="margin-right: 5px;" :href="event.task.url" v-if="showTask">{{
                            event.task.subject
                        }}
                    </Link>
                    <el-tag v-if="event.private" style="margin-right: 5px;">Private</el-tag>
                    <Time class="el-timeline-item__timestamp" :time="event.created_at"/>
                </div>
                <el-card v-if="event?.comments?.length" style="margin-top: 15px;">
                    {{ event.comments[0].content }}

<!--                    {{ JSON.stringify(event.media) }}-->

                    <ul class="el-upload-list el-upload-list--text">
                        <li class="el-upload-list__item is-success" tabindex="0" v-for="file in event.media">
                            <div class="el-upload-list__item-info"><a :href="file.original_url" target="_blank" class="el-upload-list__item-name"><i
                                class="el-icon el-icon--document">
                                <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor"
                                          d="M832 384H576V128H192v768h640V384zm-26.496-64L640 154.496V320h165.504zM160 64h480l256 256v608a32 32 0 0 1-32 32H160a32 32 0 0 1-32-32V96a32 32 0 0 1 32-32zm160 448h384v64H320v-64zm0-192h160v64H320v-64zm0 384h384v64H320v-64z"></path>
                                </svg>
                            </i><span class="el-upload-list__item-file-name">{{ file.file_name }}</span></a>
                                </div>
                        </li>
                    </ul>
                </el-card>
            </el-timeline-item>
        </el-timeline>
    </transition>

    <Pagination v-if="activities?.data" :data="activities"/>
</template>

<style lang="scss" scoped>
//.animated {
//    -webkit-animation-duration: 0.5s;
//    animation-duration: 0.5s;
//    -webkit-animation-fill-mode: both;
//    animation-fill-mode: both;
//}
//
//@keyframes fadeIn {
//    from {
//        opacity: 0;
//    }
//    to {
//        opacity: 1;
//    }
//}
//
//.fadeIn {
//    animation-name: fadeIn;
//}
</style>
