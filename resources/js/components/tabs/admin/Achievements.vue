<template>
    <div>
        <h3>{{ $t('manage-achievements') }}</h3>
        <button class="block" @click="showNewAchievement">{{ $t('add-new-achievement') }}</button>

        <BTable
            :items="achievements"
            :fields="achievementFields"
            :sort="currentSort"
            :sortAsc="currentSortAsc"
            :options="['table-sm', 'table-striped', 'table-hover']"
            class="font-sm">
            <template #actions="row">
                <FaIcon 
                    icon="fa-regular fa-pen-to-square"
                    class="icon small"
                    @click="showEditAchievement(row.item)" />
            </template>
        </BTable>

        <BModal :show="showNewAchievementModal" :footer="false" :title="$t('new-achievement')" @close="closeNewAchievement">
            <new-achievement @close="closeNewAchievement"/>
        </BModal>
        <BModal :show="showEditAchievementModal" :footer="false" :title="$t('edit-achievement')" @close="closeEditAchievement">
            <edit-achievement :achievement="achievementToEdit" @close="closeEditAchievement"/>
        </BModal>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import BTable from '../../bootstrap/BTable.vue';
import EditAchievement from '../../modals/EditAchievement.vue';
import NewAchievement from '../../modals/NewAchievement.vue';
import {ACHIEVEMENT_FIELDS, ACHIEVEMENT_DEFAULTS} from '../../../constants/achievementsConstants.js';
import BModal from '../../bootstrap/BModal.vue';

export default {
    components: {NewAchievement, EditAchievement, BModal, BTable},
    data() {
        return {
            /** @type {import('../../types/achievement').Achievement | null} */
            achievementToEdit: null,
            achievementFields: ACHIEVEMENT_FIELDS,
            currentSort: ACHIEVEMENT_DEFAULTS.currentSort,
            currentSortAsc: true,
            showNewAchievementModal: false,
            showEditAchievementModal: false,
        }
    },
    computed: {
        ...mapGetters({
            achievements: 'achievement/getAchievements',
        }),
    },
    methods: {
        /** Shows and hides the modal to create a new achievement */
        showNewAchievement() {
            this.$store.dispatch('clearErrors');
            this.showNewAchievementModal = true;
        },
        closeNewAchievement() {
            this.showNewAchievementModal = false;
        },
        /** Shows and hides the modal to edit a given achievement
         * @param {import('../../types/achievement').Achievement} achievement
         */
        showEditAchievement(achievement) {
            this.$store.dispatch('clearErrors');
            this.achievementToEdit = achievement;
            this.showEditAchievementModal = true;
        },
        closeEditAchievement() {
            this.showEditAchievementModal = false;
        },
    },
    
}
</script>