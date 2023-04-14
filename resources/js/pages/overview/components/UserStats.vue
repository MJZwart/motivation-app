<template>
    <ContentBlock title="stats" tutorial>
        <p>{{ $t('tasks-completed')}}: {{userStats.tasks_completed}}</p>
        <p v-if="userStats.repeatable_most_completed">
            {{ $t('most-completed-repeatable', [userStats.repeatable_most_completed.task_name, 
                                                userStats.repeatable_most_completed.total])}}
        </p>
    </ContentBlock>
</template>

<script setup>
defineProps({
    userStats: {
        type: Object,
        required: true,
    },
});
</script>