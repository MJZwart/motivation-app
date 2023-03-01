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
            <UserStatsVue v-if="userStats" class="mb-2" :user-stats="userStats" />
            <Timeline v-if="userId" :user-id="userId" class="mb-2" />
            <AchievementsCard v-if="achievements" class="mb-2" :achievements="achievements" />
        </div>
    </div>
</template>

<script setup lang="ts">
import AchievementsCard from './components/AchievementsCard.vue';
import RewardCard from '/js/pages/dashboard/components/reward/RewardCard.vue';
import Timeline from './components/Timeline.vue';
import {computed, onMounted, ref} from 'vue';
import {useUserStore} from '/js/store/userStore';
import {useRewardStore} from '/js/store/rewardStore';
import {Achievement} from 'resources/types/achievement';
import type {UserStats} from 'resources/types/user';
import UserStatsVue from './components/UserStats.vue';

const userStore = useUserStore();
const rewardStore = useRewardStore();

const loading = ref(true);

onMounted(async () => {
    const data = await userStore.getOverview();
    userStats.value = data.stats;
    achievements.value = data.achievements;
    loading.value = false;
});

const userId = computed(() => userStore.user?.id);

const rewardObj = computed(() => rewardStore.rewardObj);
const achievements = ref<Achievement[]>([]);
const userStats = ref<UserStats | null>(null);
</script>