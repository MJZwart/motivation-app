<template>
    <div>
        <b-form class="navbar-search">
            <b-form-input type="search" :placeholder="$t('search-user')" aria-label="Search user" v-model="data.userSearch"></b-form-input>
            <b-button type="submit" @click="searchUser">{{ $t('search') }}</b-button>
        </b-form>
    </div>
</template>


<script>
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
            this.$router.push({path: '/search'}).catch(()=>{});
            this.data.userSearch = "";
        }
    },
}
</script>


<style>
.navbar-search {
    display:flex;
    flex-direction:row;
}
</style>