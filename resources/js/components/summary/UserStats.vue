<template>
    <Summary :title="$t('stats')">
        <span>{{ $t('tasks-completed')}}: {{userStats.tasks_completed}}</span>
        <p v-if="userStats.repeatable_most_completed">
            {{ $t('most-completed-repeatable', [userStats.repeatable_most_completed.task_name, 
                                                userStats.repeatable_most_completed.total])}}
        </p>
    </Summary>
</template>

<script>
import Summary from './Summary.vue';
export default {
    components: {Summary},
    props: {
        userStats: {
            type: Object,
            required: true,
        },
    },
}
</script>