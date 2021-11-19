<template>
    <div>
        <h2>Achievements</h2>
        <b-button block @click="showNewAchievement">Add new achievement</b-button>
        <div v-for="(value, index) in achievements" :key="index">
            <p>
                <b-icon-trash-fill class="icon-small"></b-icon-trash-fill>
                <b-icon-pencil-square class="icon-small" @click="showEditAchievement(value)"></b-icon-pencil-square>
                {{value.name}}
            </p>
            <p class="silent">{{value.description}}</p>
            <p class="silent">{{value.trigger}}</p>
        </div>

        <new-achievement v-if="isNewAchievementVisible" @close="closeNewAchievement"></new-achievement>
        <edit-achievement v-if="isEditAchievementVisible" @close="closeEditAchievement" :achievement="achievementToEdit"></edit-achievement>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import EditAchievement from '../components/modals/EditAchievement.vue';
import NewAchievement from '../components/modals/NewAchievement.vue';
export default {
    components: { NewAchievement, EditAchievement },
    mounted() {
        this.$store.dispatch('admin/checkAdmin');
        this.$store.dispatch('achievement/getAllAchievements');
        this.$store.dispatch('achievement/getAchievementTriggers');
    },
    data() {
        return {
            isNewAchievementVisible: false,
            isEditAchievementVisible: false,
            achievementToEdit: null,
        }
    },
    computed: {
        ...mapGetters({
            achievements: 'achievement/getAchievements',
        }),
    },
    methods: {
        showNewAchievement() {
            this.$store.dispatch('clearInformationBlock');
            this.isNewAchievementVisible = true;
        },
        closeNewAchievement() {
            this.isNewAchievementVisible = false;
        },
        showEditAchievement(achievement) {
            this.$store.dispatch('clearInformationBlock');
            this.achievementToEdit = achievement;
            this.isEditAchievementVisible = true;
        },
        closeEditAchievement() {
            this.achievementToEdit = null;
            this.isEditAchievementVisible = false;
        },
    }
    
}
</script>


<style>

</style>