<template>
    <div>
        <h2>Achievements</h2>
        <b-button block @click="showNewAchievement">{{ $t('add-new-achievement') }}</b-button>
        <div v-for="(value, index) in achievements" :key="index">
            <p>
                <b-icon-trash-fill class="icon small" />
                <b-icon-pencil-square class="icon small" @click="showEditAchievement(value)" />
                {{value.name}}
            </p>
            <p class="silent">{{value.description}}</p>
            <p class="silent">{{value.trigger}}</p>
        </div>

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
import EditAchievement from '../components/modals/EditAchievement.vue';
import NewAchievement from '../components/modals/NewAchievement.vue';
export default {
    components: {NewAchievement, EditAchievement},
    mounted() {
        //TODO Reduce
        this.$store.dispatch('admin/checkAdmin');
        this.$store.dispatch('achievement/getAllAchievements');
        this.$store.dispatch('achievement/getAchievementTriggers');
    },
    data() {
        return {
            /** @type {import('../../types/achievement').Achievement | null} */
            achievementToEdit: null,
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