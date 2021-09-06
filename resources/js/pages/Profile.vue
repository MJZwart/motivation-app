<template>
    <div>
        <div class="profile-grid" v-if="userProfile">
            <div class="left-column">
                <character-summary  v-if="userProfile.character" class="summary-tab" :character="userProfile.character" :userCharacter="false"></character-summary>
                <div class="summary-tab" v-if="userProfile.friends">
                    <span class="frame-title">Friends 
                        <b-icon-person-plus-fill 
                            v-if="notLoggedUser" 
                            class="icon-small" 
                            @click="sendFriendRequest"></b-icon-person-plus-fill> 
                    </span>
                    <div class="side-border bottom-border">
                        <ul class="summary-list">
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
                <achievements-summary v-if="userProfile.achievements" :achievements="userProfile.achievements"></achievements-summary>
            </div>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import AchievementsSummary from '../components/summary/AchievementsSummary.vue';
import CharacterSummary from '../components/summary/CharacterSummary.vue';
export default {
    components: { CharacterSummary, AchievementsSummary },
    beforeRouteUpdate(to, from, next) {
        this.$store.dispatch('user/getUserProfile', to.params.id);
        next();
    },
    mounted() {
        this.$store.dispatch('user/getUserProfile', this.$route.params.id);
    },
    computed: {
        ...mapGetters({
            userProfile: 'user/getUserProfile',
            user: 'user/getUser',
        }),
        notLoggedUser(){
            return this.$route.params.id != this.user.id;
        },
    },
    methods: {
        sendFriendRequest(){
            this.$store.dispatch('friend/sendRequest', this.$route.params.id);
        },
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
    display: flex;
    flex-direction: column;
    gap:10px;
}
.right-column{
    grid-column-start: 2;
    grid-column-end: 4;
    display: flex;
    flex-direction: column;
    gap:10px;
}
</style>