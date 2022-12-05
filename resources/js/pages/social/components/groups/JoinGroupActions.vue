<template>
    <div class="d-flex m-2">
        Join group actions
        <button 
            v-if="!group.require_application" 
            type="button" 
            class="m-1 box-shadow" 
            @click="joinGroup()">
            {{$t('join-group')}}
        </button>
        <button 
            v-if="group.require_application" 
            type="button" 
            class="m-1 box-shadow" 
            :disabled="group.has_application"
            @click="applyToGroup()">
            {{ group.has_application ? $t('application-pending') : $t('apply-to-group')}}
        </button>
    </div>
</template>

<script setup lang="ts">
import {PropType} from 'vue';
import {useGroupStore} from '/js/store/groupStore';
import {waitingOnResponse} from '/js/services/loadingService';
import type {GroupPage} from 'resources/types/group';

const groupStore = useGroupStore();

const props = defineProps({
    group: {
        type: Object as PropType<GroupPage>,
        required: true,
    },
});

async function joinGroup() {
    if (props.group === null) return;
    await groupStore.joinGroup(props.group.id);
    waitingOnResponse.value = true;
}
async function applyToGroup() {
    if (props.group === null) return;
    await groupStore.applyToGroup(props.group.id);
    waitingOnResponse.value = true;
}
</script>