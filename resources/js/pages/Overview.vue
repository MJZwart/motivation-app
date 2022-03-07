<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <div>
                <reward-summary v-if="rewardObj" :reward="rewardObj" :userReward="true" :rewardType="rewardObj.rewardType" />
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
            <achievements-summary :achievements="achievements" />
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import AchievementsSummary from '../components/summary/AchievementsSummary.vue';
import RewardSummary from '../components/summary/RewardSummary.vue';
import Loading from '../components/Loading.vue';
export default {
    components: {RewardSummary, AchievementsSummary, Loading},
    data() {
        return {
            loading: true,
        }
    },
    mounted() {
        this.$store.dispatch('user/getOverview').then(() => this.loading = false);
    },
    computed: {
        ...mapGetters({
            rewardObj: 'reward/getRewardObj',
            user: 'user/getUser',
            achievements: 'achievement/getAchievementsByUser',
            userStats: 'user/getUserStats',
        }),
    },
    
}
</script>