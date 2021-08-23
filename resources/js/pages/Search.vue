<template>
    <div>
        <form class="search-bar">
            <input type="search" placeholder="Search user" aria-label="Search user" v-model="data.userSearch">
            <button type="submit" @click="searchUser">Search</button>
        </form>
        <br />
        <h3>Search results:</h3>
        <div v-if="searchResults">
            <div v-if="!searchResults.length">No results</div>
            <div v-for="item in searchResults" :key="item.id">{{item.full_display_name}}</div>
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