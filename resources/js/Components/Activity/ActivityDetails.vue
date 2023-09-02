<script setup>
import UserAvatar from "@/Components/User/UserAvatar.vue";

const props = defineProps({
    details: {
        required: false
    },
})
</script>

<template>
    <div v-if="details.length" class="activity-details">
        <div v-for="detail in details" class="activity-details__item">

            <!-- attached -->

            <template v-if="detail.attached">
                <i class="fa-solid fa-sliders mr-1"></i>
                Added <b>{{ detail.field }}</b>

                <template v-if="detail.field==='assignees'">
                    <span mx-2>
                        <UserAvatar
                            v-for="user in detail.attached" :key="user.id"
                            :user="user"
                            tooltip
                            :size="20"
                        />
                    </span>
                </template>
            </template>

            <!-- detached -->

            <template v-else-if="detail.detached">
                <i class="fa-solid fa-sliders mr-1"></i>
                Removed <b>{{ detail.field }}</b>

                <template v-if="detail.field==='assignees'">
                    <span mx-2>
                        <UserAvatar v-for="user in detail.detached" :key="user.id"
                                    :user="user"
                                    tooltip
                                    :size="20"
                        />
                    </span>
                </template>
            </template>

            <!-- changed -->

            <template v-else>
                <i class="fa-solid fa-sliders mr-1"></i>
                Changed <b>{{ detail.field }}</b>

                <template v-if="detail.field==='priority_id' || detail.field==='status_id'">
                    from
                    <div class="priority-tag"
                            :style="`max-width: 100px; border-color: ${detail.old.color};`"
                            :color="detail.old.color">{{ detail.old.name }}
                    </div>
                    to
                    <div class="priority-tag"
                            :style="`max-width: 100px; border-color: ${detail.new.color};`"
                            :color="detail.new.color">{{ detail.new.name }}
                    </div>
                </template>
                <tempalte v-else>
                    from <b>{{ detail.old }}</b> to <b>{{ detail.new }}</b>
                </tempalte>
            </template>
        </div>
    </div>
</template>

<style lang="scss">
.activity-details {
    color: var(--el-text-color-secondary);
    //padding-left: 10px;
    margin-left: 3px;
    margin-top: 15px;
    line-height: 25px;
    //border-left: 2px solid var(--el-border-color);
    font-size: 12px;

    &__item {
        margin-bottom: 5px;
    }
}
</style>
