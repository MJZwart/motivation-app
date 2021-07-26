<template>
    <div>
        <h2>Achievements</h2>
        <button class="long-button" @click="showNewAchievement">Add new achievement</button>
        <div v-for="(value, index) in achievements" :key="index">
            <p>
                <b-icon-trash-fill class="icon-small"></b-icon-trash-fill>
                <b-icon-pencil-square class="icon-small"></b-icon-pencil-square>
                {{value.name}}
            </p>
            <p class="silent">{{value.description}}</p>
            <p class="silent">{{value.trigger}}</p>
        </div>

        <new-achievement v-if="isNewAchievementVisible" @close="closeNewAchievement"></new-achievement>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import NewAchievement from '../components/NewAchievement.vue';
export default {
    components: { NewAchievement },
    mounted() {
        this.$store.dispatch('admin/checkAdmin');
        this.$store.dispatch('achievement/getAllAchievements');
        this.$store.dispatch('achievement/getAchievementTriggers');
    },
    data() {
        return {
            isNewAchievementVisible: false,
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
    }
    
}
</script>


<style>

</style>