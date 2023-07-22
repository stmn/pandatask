<script setup>
import {Link, usePage} from '@inertiajs/vue3'
import {computed} from "vue";
import Time from "~/js/Components/Common/TimeValue.vue";
import User from "~/js/Components/User/UserAvatar.vue";
import Pagination from "~/js/Components/Common/AppPagination.vue";
import EditorContent from "~/js/Components/Forms/EditorContent.vue";

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
    },
    onlyComments: {
        type: Boolean,
        required: false,
        default: false
    },
})

const activitiesData = computed(() => props.activities?.data || props.activities);

const page = usePage()

const auth = computed(() => page.props.auth)
</script>

<template>
    <transition name="el-fade-in-linear">
        <el-timeline style="padding: 5px 0 0 5px;">
            <template v-for="event in activitiesData">
                <el-timeline-item
                    v-if="!onlyComments || event?.type === 'task_commented'"
                    class="animated fadeIn" :key="event.id"
                    :id="`activity_${event.id}`">
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
                    <el-card v-if="event?.comment" style="margin-top: 15px;" shadow="never">
                        <EditorContent :content="event.comment.content"/>

                        <ul class="el-upload-list el-upload-list--text" v-if="event.media?.length">
                            <li class="el-upload-list__item is-success" tabindex="0" v-for="file in event.media">
                                <div class="el-upload-list__item-info"><a :href="file.original_url" target="_blank"
                                                                          class="el-upload-list__item-name"><i
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

                    <div class="changes"
                         v-if="!onlyComments"
                         style="padding-left: 10px; margin-left: 10px; margin-top: 20px; line-height: 25px; border-left: 2px solid #666; font-size: 12px;">
                        <div v-for="detail in event.details" style="margin-bottom: 5px;">
                            <template v-if="detail.attached">
                                Added <b>{{ detail.field }}</b>
                                <template v-if="detail.field==='assignees'">
                                    <User v-for="user in detail.attached" :key="user.id" :user="user"/>
                                </template>
                            </template>
                            <template v-if="detail.detached">
                                Removed <b>{{ detail.field }}</b>
                                <template v-if="detail.field==='assignees'">
                                    <User v-for="user in detail.detached" :key="user.id" :user="user"/>
                                </template>
                            </template>
                            <template v-else>
                                Changed <b>{{ detail.field }}</b>
                                <template v-if="detail.field==='priority_id' || detail.field==='status_id'">
                                    from
                                    <el-tag class="priority-tag"
                                            :style="`max-width: 100px; border-color: ${detail.old.color};`"
                                            :color="detail.old.color">{{ detail.old.name }}
                                    </el-tag>
                                    to
                                    <el-tag class="priority-tag"
                                            :style="`max-width: 100px; border-color: ${detail.new.color};`"
                                            :color="detail.new.color">{{ detail.new.name }}
                                    </el-tag>
                                </template>
                            </template>
                        </div>
                    </div>
                </el-timeline-item>
            </template>
        </el-timeline>
    </transition>

    <Pagination v-if="activities?.data" :data="activities"/>
</template>
