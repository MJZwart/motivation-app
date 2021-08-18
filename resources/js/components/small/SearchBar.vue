<template>
    <div>
        <input type="search" placeholder="Search user" aria-label="Search user" v-model="userSearch" v-on:keyup="searchUser">
    </div>
</template>


<script>
import { mapGetters } from 'vuex';
export default {
    data() {
        return {
            userSearch: "",
        }
    },
    methods: {
        searchUser() {
            this.$store.dispatch('searchUser', this.userSearch);
        }
    },
    computed: {
        ...mapGetters({
            searchResults: 'user/searchResults',
        }),
    },

}
</script>


<style>

</style>