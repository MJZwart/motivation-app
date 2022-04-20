<template>
    <Summary :title="$t('stats')">
        <span>{{ $t('tasks-completed')}}: {{userStats.tasks_completed}}</span>
        <p v-if="userStats.repeatable_most_completed">
            {{ $t('most-completed-repeatable', [userStats.repeatable_most_completed.task_name, 
                                                userStats.repeatable_most_completed.total])}}
        </p>
    </Summary>
</template>

<script setup>
import Summary from './Summary.vue';
defineProps({
    userStats: {
        type: Object,
        required: true,
    },
});
</script>