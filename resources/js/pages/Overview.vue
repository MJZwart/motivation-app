<template>
    <div>
        <div>
            <character-summary v-if="character" :character="character" :userCharacter="true" />
        </div>
        <div v-if="userStats">
            <span class="card-title">{{ $t('stats') }}</span>
            <div class="side-border bottom-border">
                <span>{{ $t('tasks-completed')}}: {{userStats.tasks_completed}}</span>
                <p v-if="userStats.repeatable_most_completed">
                    {{ $t('most-completed-repeatable', [userStats.repeatable_most_completed.task_name, 
                                                        userStats.repeatable_most_completed.total])}}
                </p>
            </div>
        </div>
        <div>
            <achievements-summary :achievements="achievements" />
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import AchievementsSummary from '../components/summary/AchievementsSummary.vue';
import CharacterSummary from '../components/summary/CharacterSummary.vue';
export default {
    components: {CharacterSummary, AchievementsSummary},
    mounted() {
        this.$store.dispatch('user/getOverview',  {root:true});
    },
    computed: {
        ...mapGetters({
            character: 'character/getCharacter',
            user: 'user/getUser',
            achievements: 'achievement/getAchievementsByUser',
            userStats: 'user/getUserStats',
        }),
    },
    
}
</script>