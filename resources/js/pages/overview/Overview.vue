<template>
    <div>
        <Loading v-if="loading" />
        <div v-else class="w-60-flex center">
            <RewardCard v-if="rewardObj" class="mb-2" :reward="rewardObj" :userReward="true" :rewardType="rewardObj.rewardType" />
            <UserStats class="mb-2" :user-stats="userStats" />
            <AchievementsCard :achievements="achievements" />
        </div>
    </div>
</template>


<script setup>
import AchievementsCard from './components/AchievementsCard.vue';
import RewardCard from '/js/pages/dashboard/components/RewardCard.vue';
import UserStats from './components/UserStats.vue';
import {computed, onMounted, ref} from 'vue';
import {useUserStore} from '/js/store/userStore';
import {useRewardStore} from '/js/store/rewardStore';
import {useAchievementStore} from '/js/store/achievementStore';

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