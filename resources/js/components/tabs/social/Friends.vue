<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <FriendRequests />
            
            <Friends :manage="true" :message="true" />
        </div>
    </div>
</template>


<script>
import Loading from '../../Loading.vue';
import Friends from '../../summary/FriendsCard.vue';
import FriendRequests from './components/FriendRequests.vue';
export default {
    components: {
        Loading, Friends, FriendRequests,
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