<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <Reward v-if="rewardObj" class="mb-2" :reward="rewardObj" :userReward="true" :rewardType="rewardObj.rewardType" />
            <UserStats class="mb-2" :user-stats="userStats" />
            <Achievements :achievements="achievements" />
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import Achievements from '../components/summary/AchievementsCard.vue';
import Reward from '../components/summary/RewardCard.vue';
import Loading from '../components/Loading.vue';
import UserStats from '../components/summary/UserStats.vue';
export default {
    components: {Reward, Achievements, Loading, UserStats},
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