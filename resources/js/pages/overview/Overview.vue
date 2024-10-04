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
import {Achievement} from 'resources/types/achievement';
import type {UserStats} from 'resources/types/user';
import UserStatsVue from './components/UserStats.vue';
import HorizontalTabControls, {TabItem} from '/js/components/global/tabs/HorizontalTabControls.vue';
import { user } from '/js/services/userService';
import axios from 'axios';
import { Reward } from 'resources/types/reward';

const tabs = ref<TabItem[]>([]);
const activeTab = ref('');
const loading = ref(true);
onMounted(async () => {
    const {data} = await axios.get('/user/overview');
    userStats.value = data.stats;
    achievements.value = data.achievements;
    tabs.value = [
        {key: 'achievements'},
        {key: 'timeline'},
        {key: 'stats'},
    ];
    if (data.rewardObj) {
        rewardObj.value = data.rewardObj;
        tabs.value.push({key: data.rewardObj?.rewardType.toLowerCase()});
    }
    activeTab.value = tabs.value[0].key;
    loading.value = false;
});

const userId = computed(() => user.value?.id);

const rewardObj = ref<Reward | null>(null);
const achievements = ref<Achievement[]>([]);
const userStats = ref<UserStats | null>(null);
</script>