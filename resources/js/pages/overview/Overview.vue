<template>
    <div>
        <Loading v-if="loading" />
        <div v-else class="w-60-flex center">
            <RewardCard
                v-if="rewardObj"
                class="mb-2"
                :reward="rewardObj"
                :userReward="true"
                :rewardType="rewardObj.rewardType"
            />
            <UserStats v-if="userStats" class="mb-2" :user-stats="userStats" />
            <Timeline v-if="userId" :user-id="userId" class="mb-2" />
            <AchievementsCard v-if="achievements" class="mb-2" :achievements="achievements" />
        </div>
    </div>
</template>

<script setup lang="ts">
import AchievementsCard from './components/AchievementsCard.vue';
import RewardCard from '/js/pages/dashboard/components/reward/RewardCard.vue';
import UserStats from './components/UserStats.vue';
import Timeline from './components/Timeline.vue';
import {computed, onMounted, ref} from 'vue';
import {useUserStore} from '/js/store/userStore';
import {useRewardStore} from '/js/store/rewardStore';

const userStore = useUserStore();
const rewardStore = useRewardStore();

const loading = ref(true);

onMounted(async () => {
    await userStore.getOverview();
    loading.value = false;
});

const userId = computed(() => userStore.user?.id);

const rewardObj = computed(() => rewardStore.rewardObj);
const achievements = computed(() => userStore.achievementsByUser);
const userStats = computed(() => userStore.userStats);
</script>
