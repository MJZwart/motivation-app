<template>
    <div>
        <div class="content-block">
            <p><b>{{ $t('your-rank')}}</b>: 
                <FaIcon :icon="group.rank == 'admin' ? 'angles-down' : 'angle-down'" />
                {{group.rank}}
            </p>
            <p><b>{{ $t('joined')}}</b>: {{parseDateTime(group.joined)}} ({{daysSince(group.joined)}})</p>
        </div>
        <div v-if="!group.is_admin" class="d-flex m-2">
            <button type="button" class="m-1 box-shadow" @click="leaveGroup()">{{ $t('leave-group') }}</button>
        </div>
    </div>
</template>

<script setup lang="ts">
import {PropType} from 'vue';
import {daysSince, parseDateTime} from '/js/services/dateService';
import {useGroupStore} from '/js/store/groupStore';
import {useI18n} from 'vue-i18n';
import type {GroupPage} from 'resources/types/group';

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