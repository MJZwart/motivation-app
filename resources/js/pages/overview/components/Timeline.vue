<template>
    <Loading v-if="!timeline" />
    <Summary v-else tutorial-off :title="$t('timeline')">
        <div class="timeline-filter">
            {{ $t('filter-by') }}:
            <template v-for="(type, index) in timelineTypes" :key="index">
                <button class="filter-button" :class="getFilterClass(type.type)" @click="toggleFilter(type.type)">
                    {{$t(type.type.toLowerCase())}}
                </button>
            </template>
        </div>
        <div class="timeline-outer">
            <ul class="timeline-list">
                <li v-for="(action, index) in filteredTimeline" :key="index" class="timeline-action">
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
import {computed, onMounted, ref} from 'vue';
import {useUserStore} from '/js/store/userStore';
import {parseDateTime} from '/js/services/dateService';
import {useI18n} from 'vue-i18n';
import TimelineIcon from './TimelineIcon.vue';
const {t} = useI18n();

const props = defineProps<{userId?: number}>();

const userStore = useUserStore();

type TimelineAction = {
    timestamp: string,
    type: string,
    action: string,
    params: string,
}

const timeline = ref<TimelineAction[] | null>(null);
const timelineTypes = ref<{type: string}[] | null>(null);

const filteredTimeline = computed(() => {
    return timeline.value?.filter(item => !inactiveFilters.value.includes(item.type));
});

onMounted(async() => {
    const data = await userStore.getTimeline(props.userId);
    timeline.value = data.timeline;
    timelineTypes.value = data.types
});

function parseTimelineAction(action: TimelineAction) {
    const params = action.params ? JSON.parse(action.params) : null;
    return params ? t(action.action, params) : t(action.action);
}

const inactiveFilters = ref<string[]>([]);

function toggleFilter(type: string) {
    if (inactiveFilters.value.includes(type)) inactiveFilters.value.splice(inactiveFilters.value.indexOf(type), 1);
    else inactiveFilters.value.push(type);
}
function getFilterClass(type: string) {
    return inactiveFilters.value.includes(type) ? 'inactive' : 'active';
}

</script>

<style lang="scss" scoped>
.timeline-outer {
    max-height: 250px;
    overflow-y: auto;
    .timeline-list {
        list-style-type: none;
        position: relative;
        padding: 0;
        li {
            margin: 10px 0;
            padding-left: 20px;
            position: relative;
        }
    }
    .timeline-list::before {
        content: ' ';
        background: var(--border-color);
        position: absolute;
        top: 8px;
        left: 7px;
        width: 2px;
        height: calc(100% - 11px);
    }
    .timeline-action::before {
        content: ' ';
        background: var(--background-2);
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
.timeline-filter {
    .filter-button {
        border-radius: 1.25rem;
        padding: 0.1rem 0.3rem;
        margin-right: 0.2rem;
        margin-left: 0.2rem;
    }
    .filter-button.active {
        background-color: var(--action-button);
        border-color: var(--action-button);
    }
    .filter-button.inactive {
        background-color: var(--button);
        color: var(--grey);
    }
    .filter-button.inactive:hover {
        border-color: var(--button);
    }
}
</style>