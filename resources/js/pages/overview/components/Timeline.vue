<template>
    <Loading v-if="!timeline" />
    <Summary v-else tutorial-off :title="$t('timeline')">
        <div class="timeline-outer">
            <ul class="timeline-list">
                <li v-for="(action, index) in timeline" :key="index" class="timeline-action">
                    <span class="timeline-timestamp">
                        {{ parseDateTime(action.timestamp, true) }}
                    </span>
                    <span class="timeline-type">
                        <TimelineIcon :type="action.type" />
                        {{ $t(action.type.toLowerCase()) }}
                    </span>
                    {{ parseTimelineAction(action) }}
                </li>
            </ul>
        </div>
    </Summary>
</template>

<script setup lang="ts">
import Summary from '/js/components/global/Summary.vue';
import {onMounted, ref} from 'vue';
import {useUserStore} from '/js/store/userStore';
import {parseDateTime} from '/js/services/dateService';
import {useI18n} from 'vue-i18n';
import TimelineIcon from './TimelineIcon.vue';
const {t} = useI18n();

const userStore = useUserStore();

type TimelineAction = {
    timestamp: Date | string,
    type: string,
    action: string,
    params: string,
}

const timeline = ref<TimelineAction[] | null>(null);

onMounted(async() => {
    timeline.value = await userStore.getTimeline();
});

function parseTimelineAction(action: TimelineAction) {
    const params = action.params ? JSON.parse(action.params) : null;
    return params ? t(action.action, params) : t(action.action);
}

</script>

<style lang="scss" scoped>
.timeline-outer {
    .timeline-list {
        list-style-type: none;
        position: relative;
        padding: 0;
        margin: 0;
        li {
            margin: 10px 0;
            padding-left: 20px;
            position: relative;
        }
    }
    .timeline-list::before {
        content: ' ';
        background: var(--border-color);
        display: inline-block;
        position: absolute;
        top: 8px;
        left: 7px;
        width: 2px;
        height: calc(100% - 11px);
    }
    .timeline-action::before {
        content: ' ';
        background: var(--background-2);
        display: inline-block;
        position: absolute;
        border-radius: 50%;
        border: 2px solid var(--primary-as-text);
        left: 3px;
        top: 8px;
        width: 8px;
        height: 8px;
    }
    .timeline-timestamp {
        min-width: 150px;
        display: inline-block;
    }
    .timeline-type{
        min-width: 120px;
        display: inline-block;
    }
}
</style>