<template>
    <Loading v-if="loading" />
    <div v-else class="w-60-flex center">
        <HorizontalTabControls v-model="activeTab" class="mb-2" :tabs="tabs" />
        <KeepAlive>
            <Timeline v-if="userId && activeTab === 'timeline'" :user-id="userId" />
        </KeepAlive>
        <RewardCard
            v-if="rewardObj && activeTab === rewardObj?.rewardType.toLowerCase()"
            :reward="rewardObj"
            :userReward="true"
            :rewardType="rewardObj.rewardType"
        />
        <UserStatsVue v-if="userStats && activeTab === 'stats'" :user-stats="userStats" />
        <AchievementsCard v-if="achievements && activeTab === 'achievements'" :achievements="achievements" />
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
import HorizontalTabControls, {TabItem} from '/js/components/global/tabs/HorizontalTabControls.vue';

const userStore = useUserStore();
const rewardStore = useRewardStore();

const tabs = ref<TabItem[]>([]);
const activeTab = ref('');
const loading = ref(true);
onMounted(async () => {
    const data = await userStore.getOverview();
    userStats.value = data.stats;
    achievements.value = data.achievements;
    tabs.value = [
        {key: 'achievements'},
        {key: 'timeline'},
        {key: 'stats'},
    ];
    if (rewardObj.value) tabs.value.push({key: rewardObj.value?.rewardType.toLowerCase()});
    activeTab.value = tabs.value[0].key;
    loading.value = false;
});

const userId = computed(() => userStore.user?.id);

const rewardObj = computed(() => rewardStore.rewardObj);
const achievements = ref<Achievement[]>([]);
const userStats = ref<UserStats | null>(null);
</script>