<template>
    <div>
        <h2>Login</h2>

        <div class="login-form">
            <form @submit.prevent="submitLogin">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Username" 
                        v-model="login.username" />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Password" 
                        v-model="login.password" />
                </div>
                <div class="form-group">
                    <button type="submit" class="long-button">Login</button>
                </div>
            </form> 
        </div>
    </div>
</template>


<script>
export default {
    data(){
        return {
            login: {
                username: '',
                password: ''
            }
        }
    },
    methods: {
        submitLogin(){
            this.$store.dispatch('user/login', this.login);
        }
    }
}
</script>


<style>
    .login-form{
        width:40%;
        margin: auto auto;
    }
</style>