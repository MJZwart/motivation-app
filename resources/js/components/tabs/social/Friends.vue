<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <FriendRequests />
            
            <ManageFriends />
        </div>
    </div>
</template>


<script>
import Loading from '../../Loading.vue';
import ManageFriends from './components/ManageFriends.vue';
import FriendRequests from './components/FriendRequests.vue';
export default {
    components: {
        Loading, ManageFriends, FriendRequests,
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