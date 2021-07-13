<template>
    <div>
        <div class="profile-grid" v-if="userProfile">
            <div class="left-column">
                <character-summary class="character-tab" :character="userProfile.character"></character-summary>
                <div class="friends">
                    <span class="frame-title">Friends <b-icon-person-plus-fill class="icon-small"></b-icon-person-plus-fill> </span>
                    <div class="side-border bottom-border">
                        <ul class="friends-list">
                            <li v-for="(value, index) in userProfile.friends" :key="index">
                                {{value.friend}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="right-column">
                <h2>{{userProfile.full_display_name}}</h2>
                <p class="silent">Member since: {{userProfile.created_at}}</p>
                <achievements-summary :achievements="userProfile.achievements"></achievements-summary>
            </div>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import AchievementsSummary from '../components/AchievementsSummary.vue';
import CharacterSummary from '../components/CharacterSummary.vue';
export default {
    components: { CharacterSummary, AchievementsSummary },
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