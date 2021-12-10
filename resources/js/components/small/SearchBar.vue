<template>
    <div>
        <b-form class="navbar-search">
            <b-form-input type="search" placeholder="Search user" aria-label="Search user" v-model="data.userSearch"></b-form-input>
            <b-button type="submit" @click="searchUser">Search</b-button>
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