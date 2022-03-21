<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <FriendRequests />
            
            <FriendsCard :manage="true" :message="true" />
        </div>
    </div>
</template>


<script>
import Loading from '../../Loading.vue';
import FriendsCard from '../../summary/FriendsCard.vue';
import FriendRequests from './components/FriendRequests.vue';
export default {
    components: {
        Loading, FriendsCard, FriendRequests,
    },
    mounted() {
        this.$store.dispatch('friend/getRequests').then(() => this.loading = false);
    },
    data() {
        return {
            loading: true,
        }
    },

}
</script>