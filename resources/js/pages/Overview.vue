<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <RewardCard v-if="rewardObj" class="mb-2" :reward="rewardObj" :userReward="true" :rewardType="rewardObj.rewardType" />
            <UserStats class="mb-2" :user-stats="userStats" />
            <AchievementsCard :achievements="achievements" />
        </div>
    </div>
</template>


<script setup>
import AchievementsCard from '../components/summary/AchievementsCard.vue';
import RewardCard from '../components/summary/RewardCard.vue';
import Loading from '../components/Loading.vue';
import UserStats from '../components/summary/UserStats.vue';
import {computed, onMounted, ref} from 'vue';
import {useUserStore} from '@/store/userStore';
import {useRewardStore} from '@/store/rewardStore';
import {useAchievementStore} from '@/store/achievementStore';

const userStore = useUserStore();
const rewardStore = useRewardStore();
const achievementStore = useAchievementStore();

const loading = ref(true);

onMounted(async() => {
    await userStore.getOverview()
    loading.value = false;
});

const rewardObj = computed(() => rewardStore.rewardObj);
const achievements = computed(() => achievementStore.achievementsByUser);
const userStats = computed(() => userStore.userStats);
</script>