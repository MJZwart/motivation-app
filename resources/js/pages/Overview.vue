<template>
    <div>
        <div>
            <character-summary :character="character"></character-summary>
        </div>
        <div v-if="userStats">
            <span class="frame-title">Stats</span>
            <div class="side-border bottom-border">
                <span>Tasks completed: {{userStats.tasks_completed}}</span>
                <p v-if="userStats.repeatable_most_completed">Most completed repeatable: {{userStats.repeatable_most_completed.task_name}}. Completed {{userStats.repeatable_most_completed.total}} times.</p>
            </div>
        </div>
        <div>
            <achievements-summary :achievements="achievements"></achievements-summary>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import AchievementsSummary from '../components/AchievementsSummary.vue';
import CharacterSummary from '../components/CharacterSummary.vue';
export default {
    components: {CharacterSummary, AchievementsSummary},
    mounted() {
        this.$store.dispatch('character/getCharacter', { root:true });
        this.$store.dispatch('achievement/getAchievements', this.user.id);
        this.$store.dispatch('user/getUserStats', { root:true });
    },
    data() {
        return {
            
        }
    },
    computed: {
        ...mapGetters({
            character: 'character/getCharacter',
            user: 'user/getUser',
            achievements: 'achievement/getAchievements',
            userStats: 'user/getUserStats',
        }),
    },
    
}
</script>



<style>

</style>