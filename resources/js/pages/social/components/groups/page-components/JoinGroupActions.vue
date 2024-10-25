<template>
    <div class="d-flex m-2">
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
import {waitingOnResponse} from '/js/services/loadingService';
import type {GroupPage} from 'resources/types/group';
import axios from 'axios';
import { groupPage } from '/js/services/groupService';

const props = defineProps({
    group: {
        type: Object as PropType<GroupPage>,
        required: true,
    },
});

async function joinGroup() {
    if (props.group === null) return;
    const {data} = await axios.post(`/groups/join/${props.group.id}`);
    groupPage.value = data.data.group;
    waitingOnResponse.value = true;
}
async function applyToGroup() {
    if (props.group === null) return;    
    const {data} = await axios.post(`/groups/apply/${props.group.id}`);
    groupPage.value = data.data.group;
    waitingOnResponse.value = true;
}
</script>