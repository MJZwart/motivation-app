<template>
    <div>
        <ul class="navbar">
            <li>
                <router-link to="/" exact>Home</router-link>
            </li>
            <li>
                <router-link to="/character">Character</router-link>
            </li>
            <div class="flex-end">
                <span v-if="authenticated">{{user.username}}</span>
                <li v-if="!authenticated">
                    <router-link to="/login">Login</router-link>
                </li>
                <li v-if="!authenticated">
                    <router-link to="/register">Register</router-link>
                </li>
                <li v-if="authenticated">
                    <button @click="logout" type="submit" class="logout-button">Logout</button>
                </li>
            </div>
        </ul>
    </div>
</template>


<script>
export default {
    computed: {
        authenticated() {
            return this.$store.getters.authenticated;
        },
        user() {
            return this.$store.getters.getUser;
        },
    },

    methods: {
        logout(){
            this.$store.dispatch('logout');
        },
    },
}
</script>


<style>
    .navbar{
        display: flex;
    }
    .navbar li{
        padding: 10px;
        list-style: none;
        font-size: 1.5rem;
        font-weight: 500;
    }
    .navbar li a{
        color:black;
        text-decoration: none;
    }
    .navbar li a.router-link-active{
        color: teal;
        text-decoration: none;
    }
    .navbar li a:hover{
        color: rgba(0, 128, 128, 0.658);
    }
    .navbar .flex-end{
        display: flex;
        margin-left: auto;
    }
</style>