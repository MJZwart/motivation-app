<template>
    <div>
        <h3>{{ $t('manage-achievements') }}</h3>
        <b-button block @click="showNewAchievement">{{ $t('add-new-achievement') }}</b-button>

        <b-table
            :items="achievements"
            :fields="achievementFields"
            :sort-by.sync="currentSort"
            :sort-desc.sync="currentSortDesc"
            hover small
            class="font-sm">
            <template #cell(actions)="data">
                <!-- <b-icon-trash-fill class="icon small" /> -->
                <b-icon-pencil-square class="icon small" @click="showEditAchievement(data.item)" />
            </template>
        </b-table>

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
import EditAchievement from '../../modals/EditAchievement.vue';
import NewAchievement from '../../modals/NewAchievement.vue';
import {ACHIEVEMENT_FIELDS, ACHIEVEMENT_DEFAULTS} from '../../../constants/achievementsConstants.js';
import BModal from '../../bootstrap/BModal.vue';

export default {
    components: {NewAchievement, EditAchievement, BModal},
    data() {
        return {
            /** @type {import('../../types/achievement').Achievement | null} */
            achievementToEdit: null,
            achievementFields: ACHIEVEMENT_FIELDS,
            currentSort: ACHIEVEMENT_DEFAULTS.currentSort,
            currentSortDesc: false,
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