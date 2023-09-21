<template>
    <div>
        <div class="content-block">
            <p><b>{{ $t('your-rank')}}</b>: 
                <GroupRankIcon :rank="group.rank" />
                {{group.rank.name}}
            </p>
            <p><b>{{ $t('joined')}}</b>: {{parseDateTime(group.joined)}} ({{daysSince(group.joined)}})</p>
            <p><b>{{ $t('contribution-today') }}</b>: {{ parseBigNumbers(group.your_exp_today) }}</p>
            <p><b>{{ $t('contribution-total') }}</b>: {{ parseBigNumbers(group.your_exp_total) }}</p>
        </div>
        <div v-if="!group.rank.owner" class="d-flex m-2">
            <button type="button" class="m-1 box-shadow" @click="leaveGroup()">{{ $t('leave-group') }}</button>
        </div>
    </div>
</template>

<script setup lang="ts">
import type {GroupPage} from 'resources/types/group';
import {PropType} from 'vue';
import {daysSince, parseDateTime} from '/js/services/dateService';
import {parseBigNumbers} from '/js/services/numberService';
import {useGroupStore} from '/js/store/groupStore';
import {useI18n} from 'vue-i18n';
import GroupRankIcon from './GroupRankIcon.vue';

const {t} = useI18n();

const groupStore = useGroupStore();

const props = defineProps({
    group: {
        type: Object as PropType<GroupPage>,
        required: true,
    },
});

async function leaveGroup() {
    if (props.group === null) return;
    if (confirm(t('leave-group-confirm', {group: props.group.name}))) {
        await groupStore.leaveGroup(props.group.id)
    }
}
</script>