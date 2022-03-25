<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <FriendRequests class="mb-2" />
            
            <FriendsCard :manage="true" :message="true" class="mb-2" />

            <SearchUser @reload="load" />
        </div>
    </div>
</template>


<script>
import Loading from '../../Loading.vue';
import FriendsCard from '../../summary/FriendsCard.vue';
import FriendRequests from './components/FriendRequests.vue';
import SearchUser from './components/SearchUser.vue';
export default {
    components: {
        Loading, FriendsCard, FriendRequests, SearchUser,
    },
    mounted() {
        this.load();
    },
    data() {
        return {
            loading: true,
        }
    },
    methods: {
        load() {
            this.loading = true;
            this.$store.dispatch('friend/getRequests').then(() => this.loading = false);
        },
    },

}
</script>