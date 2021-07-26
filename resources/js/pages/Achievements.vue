<template>
    <div>
        <h2>Achievements</h2>
        <button class="long-button">Add new achievement</button>
        <div v-for="(value, index) in achievements" :key="index">
            <p>
                <b-icon-trash-fill class="icon-small"></b-icon-trash-fill>
                <b-icon-pencil-square class="icon-small"></b-icon-pencil-square>
                {{value.name}}
            </p>
            <p class="silent">{{value.description}}</p>
            <p class="silent">{{value.trigger}}</p>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
export default {
    mounted() {
        this.$store.dispatch('admin/checkAdmin');
        this.$store.dispatch('achievement/getAllAchievements');
    },
    computed: {
        ...mapGetters({
            achievements: 'achievement/getAchievements',
        }),
    }
    
}
</script>


<style>

</style>