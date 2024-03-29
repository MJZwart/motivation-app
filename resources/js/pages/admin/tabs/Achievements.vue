<template>
    <div>
        <h3>{{ $t('manage-achievements') }}</h3>
        <button class="block" @click="showNewAchievement">{{ $t('add-new-achievement') }}</button>

        <Table
            v-if="achievements"
            :items="achievements"
            :fields="achievementFields"
            :sort="currentSort"
            :sortAsc="currentSortAsc"
            :options="['table-sm', 'table-striped', 'table-hover', 'page-wide']"
            :items-per-page="15"
            class="font-sm"
        >
            <template #trigger="row">
                {{parseAchievementTriggerDesc(row.item)}}
            </template>
            <template #actions="row">
                <Icon :icon="EDIT" class="icon small edit-icon" @click="showEditAchievement(row.item)" />
            </template>
        </Table>
    </div>
</template>

<script setup lang="ts">
import {ref, onMounted} from 'vue';
import Table from '/js/components/global/Table.vue';
import {ACHIEVEMENT_FIELDS, ACHIEVEMENT_DEFAULTS} from '/js/constants/achievementsConstants.js';
import {useAchievementStore} from '/js/store/achievementStore';
import {Achievement, NewAchievement} from 'resources/types/achievement';
import {newAchievementInstance, parseAchievementTriggerDesc} from '/js/services/achievementService';
import {EDIT} from '/js/constants/iconConstants';
import {formModal} from '/js/components/modal/modalService';
import CreateEditAchievement from '../components/CreateEditAchievement.vue';

const achievementStore = useAchievementStore();

onMounted(async() => {
    achievements.value = await achievementStore.getAllAchievements();
});

const achievements = ref<Achievement[]>([]);

const achievementFields = ACHIEVEMENT_FIELDS;
const currentSort = ref(ACHIEVEMENT_DEFAULTS.currentSort);
const currentSortAsc = ref(true);

/** Shows and hides the modal to create a new achievement */
function showNewAchievement() {
    formModal(newAchievementInstance(), CreateEditAchievement, submitNewAchievement, 'new-achievement');
}
async function submitNewAchievement(newAchievement: NewAchievement) {
    achievements.value = await achievementStore.newAchievement(newAchievement);
}
/** Shows and hides the modal to edit a given achievement */
function showEditAchievement(achievement: Achievement) {
    formModal(achievement, CreateEditAchievement, submitEditAchievement, 'edit-achievement');
}
async function submitEditAchievement(achievement: Achievement) {
    achievements.value = await achievementStore.editAchievement(achievement);
}
</script>
