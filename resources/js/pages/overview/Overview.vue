<template>
    <Loading v-if="loading" />
    <div v-else class="w-60-flex center">
        <HorizontalTabControls v-model="activeTab" class="mb-2" :tabs="tabs" />
        <KeepAlive>
            <Timeline v-if="userId && activeTab === 'timeline'" :user-id="userId" />
        </KeepAlive>
        <VillageCard
            v-if="village && activeTab === 'village'"
            :village="village"
        />
        <UserStatsVue v-if="userStats && activeTab === 'stats'" :user-stats="userStats" />
        <AchievementsCard v-if="achievements && activeTab === 'achievements'" :achievements="achievements" />
    </div>
</template>

<script setup lang="ts">
import AchievementsCard from './components/AchievementsCard.vue';
import VillageCard from '/js/pages/dashboard/components/village/VillageCard.vue';
import Timeline from './components/Timeline.vue';
import {computed, onMounted, ref} from 'vue';
import {Achievement} from 'resources/types/achievement';
import type {UserStats} from 'resources/types/user';
import UserStatsVue from './components/UserStats.vue';
import HorizontalTabControls, {TabItem} from '/js/components/global/tabs/HorizontalTabControls.vue';
import {user} from '/js/services/userService';
import axios from 'axios';
import {Village} from 'resources/types/village';

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
    if (data.village) {
        village.value = data.village;
        tabs.value.push({key: 'village'});
    }
    activeTab.value = tabs.value[0].key;
    loading.value = false;
});

const userId = computed(() => user.value?.id);

const village = ref<Village | null>(null);
const achievements = ref<Achievement[]>([]);
const userStats = ref<UserStats | null>(null);
</script>