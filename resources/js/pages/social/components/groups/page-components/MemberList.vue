<template>
    <div class="content-block">
        <p><b>{{ $t('members')}}</b>:</p>
        <div class="row">
            <b class="col">{{ $t('username')}}</b>
            <b class="col">{{ $t('rank')}}</b>
            <b class="col">{{ $t('member-for')}}</b>
        </div>
        <div v-for="(member, index) in group.members" :key="index" class="row">
            <span class="col">{{member.username}}</span>
            <span class="col"><FaIcon :icon="member.rank == 'admin' ? 'angles-down' : 'angle-down'" />
                {{member.rank}}</span>
            <span class="col">{{daysSince(member.joined.toString())}}</span>
        </div>
    </div>
</template>

<script setup lang="ts">
import {PropType} from 'vue';
import {daysSince} from '/js/services/dateService';
import type {GroupPage} from 'resources/types/group';

defineProps({
    group: {
        type: Object as PropType<GroupPage>,
        required: true,
    },
});
</script>