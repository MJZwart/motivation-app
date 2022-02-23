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

        <b-modal id="new-achievement" hide-footer :title="$t('new-achievement')">
            <new-achievement @close="closeNewAchievement"/>
        </b-modal>
        <b-modal id="edit-achievement" hide-footer :title="$t('edit-achievement')">
            <edit-achievement :achievement="achievementToEdit" @close="closeEditAchievement"/>
        </b-modal>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import EditAchievement from '../../modals/EditAchievement.vue';
import NewAchievement from '../../modals/NewAchievement.vue';
import {ACHIEVEMENT_FIELDS, ACHIEVEMENT_DEFAULTS} from '../../../constants/achievementsConstants.js';

export default {
    components: {NewAchievement, EditAchievement},
    data() {
        return {
            /** @type {import('../../types/achievement').Achievement | null} */
            achievementToEdit: null,
            achievementFields: ACHIEVEMENT_FIELDS,
            currentSort: ACHIEVEMENT_DEFAULTS.currentSort,
            currentSortDesc: false,
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
            this.$bvModal.show('new-achievement');
        },
        closeNewAchievement() {
            this.$bvModal.hide('new-achievement');
        },
        /** Shows and hides the modal to edit a given achievement
         * @param {import('../../types/achievement').Achievement} achievement
         */
        showEditAchievement(achievement) {
            this.$store.dispatch('clearErrors');
            this.achievementToEdit = achievement;
            this.$bvModal.show('edit-achievement');
        },
        closeEditAchievement() {
            this.$bvModal.hide('edit-achievement');
        },
    },
    
}
</script>