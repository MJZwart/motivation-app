<template>
    <div>
        <div class="profile-grid" v-if="userProfile">
            <div class="left-column">
                <character-summary class="character-tab" :character="userProfile.character"></character-summary>
                <p>Achievements</p>
                <p>Friends</p>
            </div>
            <div class="right-column">
                <h2>{{userProfile.full_display_name}}</h2>
                <p class="silent">Member since: {{userProfile.created_at}}</p>
            </div>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import CharacterSummary from '../components/CharacterSummary.vue';
export default {
    components: { CharacterSummary },
    mounted() {
        this.$store.dispatch('getUserProfile', this.$route.params.id);
    },
    computed: {
        ...mapGetters({
            userProfile: 'getUserProfile',
        }),
    },
}
</script>


<style>
.profile-grid{
    display: grid;
    gap:10px;
}
.left-column{
    grid-column-start: 1;
}
.right-column{
    grid-column-start: 2;
    grid-column-end: 4;
}
</style>