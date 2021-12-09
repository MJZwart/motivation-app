<template>
    <div>
        <b-form class="search-bar">
            <input type="search" placeholder="Search user" aria-label="Search user" v-model="data.userSearch">
            <b-button type="submit" @click="searchUser">{{ $t('search') }}</b-button>
        </b-form>
        <br />
        <h3>{{ $t('search-results') }}:</h3>
        <div v-if="searchResults">
            <div v-if="!searchResults.length">{{ $t('no-results') }}</div>
            <div v-for="user in searchResults" :key="user.id">
                <router-link :to="{ name: 'profile', params: { id: user.id}}">{{user.username}}</router-link>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    data() {
        return {
            data: {
                userSearch: "",
            },
        }
    },
    methods: {
        searchUser() {
            this.$store.dispatch('user/searchUser', this.data);
            this.data.userSearch = "";
        },
    },
    computed: {
        ...mapGetters({
            searchResults: 'user/getSearchResults',
        }),
    },
};
</script>

<style>
.search-bar{
    display:flex;
    width:70%;
    flex-direction:row;
}
</style>