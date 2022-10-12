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
            :options="['table-sm', 'table-striped', 'table-hover']"
            class="font-sm"
        >
            <template #trigger="row">
                {{parseAchievementTriggerDesc(row.item)}}
            </template>
            <template #actions="row">
                <FaIcon :icon="['far', 'pen-to-square']" class="icon small" @click="showEditAchievement(row.item)" />
            </template>
        </Table>

        <Modal
            :show="showNewAchievementModal"
            :footer="false"
            :title="$t('new-achievement')"
            @close="closeNewAchievement"
        >
            <NewAchievement @close="closeNewAchievement" />
        </Modal>
        <Modal
            :show="showEditAchievementModal"
            :footer="false"
            :title="$t('edit-achievement')"
            @close="closeEditAchievement"
        >
            <EditAchievement v-if="achievementToEdit" :achievement="achievementToEdit" @close="closeEditAchievement" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import {ref, computed} from 'vue';
import Table from '/js/components/global/Table.vue';
import EditAchievement from '/js/pages/admin/components/EditAchievement.vue';
import NewAchievement from '/js/pages/admin/components/NewAchievement.vue';
import {ACHIEVEMENT_FIELDS, ACHIEVEMENT_DEFAULTS} from '/js/constants/achievementsConstants.js';
import {useAchievementStore} from '/js/store/achievementStore';
import {useMainStore} from '/js/store/store';
import {Achievement} from 'resources/types/achievement';
import {parseAchievementTriggerDesc} from '/js/services/achievementService';
const achievementStore = useAchievementStore();
const mainStore = useMainStore();

const achievementToEdit = ref<Achievement | null>(null);
const achievementFields = ACHIEVEMENT_FIELDS;
const currentSort = ref(ACHIEVEMENT_DEFAULTS.currentSort);
const currentSortAsc = ref(true);
const showNewAchievementModal = ref(false);
const showEditAchievementModal = ref(false);

const achievements = computed(() => achievementStore.achievements);

/** Shows and hides the modal to create a new achievement */
function showNewAchievement() {
    mainStore.clearErrors();
    showNewAchievementModal.value = true;
}
function closeNewAchievement() {
    showNewAchievementModal.value = false;
}
/** Shows and hides the modal to edit a given achievement */
function showEditAchievement(achievement: Achievement) {
    mainStore.clearErrors();
    achievementToEdit.value = achievement;
    showEditAchievementModal.value = true;
}
function closeEditAchievement() {
    showEditAchievementModal.value = false;
}
</script>
