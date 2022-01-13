<template>
    <div>
        <form class="navbar-search">
            <b-form-input v-model="data.userSearch" type="search" :placeholder="$t('search-user')" aria-label="Search user" />
            <b-button type="submit" @click="searchUser">{{ $t('search') }}</b-button>
        </form>
    </div>
</template>


<script>
export default {
    data() {
        return {
            data: {
                userSearch: '',
            },
        }
    },
    methods: {
        /** Searches for a user by their username, case-insensitive and includes all that contains the search params */
        searchUser() {
            this.$store.dispatch('user/searchUser', this.data);
            this.$router.push({path: '/search'}).catch(() => {});
            this.data.userSearch = '';
        },
    },
}
</script>


<style>
.navbar-search {
    display:flex;
    flex-direction:row;
}
</style>