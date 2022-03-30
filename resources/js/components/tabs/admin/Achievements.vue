<template>
    <div>
        <h3>{{ $t('manage-achievements') }}</h3>
        <b-button block @click="showNewAchievement">{{ $t('add-new-achievement') }}</b-button>

        <BTable
            :items="achievements"
            :fields="achievementFields"
            :sort="currentSort"
            :sortAsc="currentSortAsc"
            :options="['table-sm', 'table-striped', 'table-hover']"
            class="font-sm">
            <template #actions="row">
                <b-icon-pencil-square class="icon small" @click="showEditAchievement(row.item)" />
            </template>
        </BTable>

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
import BTable from '../../bootstrap/BTable.vue';
import EditAchievement from '../../modals/EditAchievement.vue';
import NewAchievement from '../../modals/NewAchievement.vue';
import {ACHIEVEMENT_FIELDS, ACHIEVEMENT_DEFAULTS} from '../../../constants/achievementsConstants.js';

export default {
    components: {NewAchievement, EditAchievement, BTable},
    data() {
        return {
            /** @type {import('../../types/achievement').Achievement | null} */
            achievementToEdit: null,
            achievementFields: ACHIEVEMENT_FIELDS,
            currentSort: ACHIEVEMENT_DEFAULTS.currentSort,
            currentSortAsc: true,
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