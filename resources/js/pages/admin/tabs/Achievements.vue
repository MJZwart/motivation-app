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
                <Icon :icon="TRASH" class="icon small delete-icon red" @click="deleteAchievement(row.item)" />
            </template>
        </Table>
    </div>
</template>

<script setup lang="ts">
import {ref, onMounted} from 'vue';
import Table from '/js/components/global/Table.vue';
import {ACHIEVEMENT_FIELDS, ACHIEVEMENT_DEFAULTS} from '/js/constants/achievementsConstants.js';
import {Achievement, NewAchievement} from 'resources/types/achievement';
import {parseAchievementTriggerDesc} from '/js/helpers/stringHelper';
import {EDIT, TRASH} from '/js/constants/iconConstants';
import {formModal, confirmModal} from '/js/components/modal/modalService';
import CreateEditAchievement from '../components/CreateEditAchievement.vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const {t} = useI18n();

onMounted(async() => {
    const {data} = await axios.get('/admin/achievements');
    achievements.value = data.data;
});

const achievements = ref<Achievement[]>([]);

const achievementFields = ACHIEVEMENT_FIELDS;
const currentSort = ref(ACHIEVEMENT_DEFAULTS.currentSort);
const currentSortAsc = ref(true);

/** Shows and hides the modal to create a new achievement */
function showNewAchievement() {
    formModal(newAchievementInstance(), CreateEditAchievement, submitNewAchievement, 'new-achievement');
}
function newAchievementInstance(): NewAchievement {
    return {
        description: '',
        name: '',
        trigger_amount: 0,
        trigger_type: '',
    };
}

async function submitNewAchievement(newAchievement: NewAchievement) {
    // TODO Rather than sending back all achievements, send only one back and process it here
    const {data} = await axios.post('/admin/achievements', newAchievement);
    achievements.value = data.data.achievements;
}
/** Shows and hides the modal to edit a given achievement */
function showEditAchievement(achievement: Achievement) {
    formModal(achievement, CreateEditAchievement, submitEditAchievement, 'edit-achievement');
}
async function submitEditAchievement(achievement: Achievement) {
    // TODO Rather than sending back all achievements, send only one back and process it here
    const {data} = await axios.put('/admin/achievements/' + achievement.id, achievement);
    achievements.value =  data.data.achievements;
}
/** Shows and hides the modal to delete a given achievement */
function deleteAchievement(achievement: Achievement) {
    confirmModal(
        t('delete-achievement-confirm'),
        async() => {
            const {data} = await axios.delete('/admin/achievements/' + achievement.id);
            achievements.value = data.data.achievements;
        },
        'delete-achievement');
}
</script>
