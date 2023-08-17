<template>
    <div class="content-block">
        <p><b>{{ $t('level')}}</b>: {{ group.level }}</p>
        <ProgressBar :value="group.experience" :max="group.exp_to_next_level" />
        <p><b>{{ $t('admin')}}</b>: {{group.admin.username}}</p>
        <p><b>{{ $t('description')}}</b>: {{group.description}}</p>
        <p><b>{{ $t('founded')}}</b>: 
            {{parseDateTime(group.time_created)}} ({{daysSince(group.time_created.toString())}})</p>
        <p>
            {{group.is_public ? $t('public') : $t('private')}}
            {{group.is_public && group.require_application ? ': ' + $t('requires-application') : ''}}
        </p>
    </div>
</template>

<script setup lang="ts">
import type {GroupPage} from 'resources/types/group';
import {daysSince, parseDateTime} from '/js/services/dateService';
import ProgressBar from '/js/components/global/ProgressBar.vue';

defineProps<{group: GroupPage}>();
</script>