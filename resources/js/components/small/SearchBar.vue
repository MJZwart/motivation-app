<template>
    <div>
        <form class="navbar-search">
            <input type="search" placeholder="Search user" aria-label="Search user" v-model="data.userSearch">
            <b-button type="submit" @click="searchUser">Search</b-button>
        </form>
    </div>
</template>


<script>
export default {
    //TODO Turn this back on
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