<template>
    <div class="w-60 m-auto">
        <b-form class="search-bar">
            <b-form-input type="search" placeholder="Search user" aria-label="Search user" v-model="data.userSearch" />
            <b-button type="submit" @click="searchUser">Search</b-button>
        </b-form>
        <br />
        <h3>Search results:</h3>
        <div v-if="searchResults">
            <div  v-for="user in searchResults" :key="user.id">
                <router-link :to="{ name: 'profile', params: { id: user.id}}">{{user.username}}</router-link>
            </div>
        </div>
        <div v-else>No results</div>
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
    flex-direction:row;
}
</style>