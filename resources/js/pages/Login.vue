<template>
    <div>
        <div class="login-form">
        <h2>Login</h2>
            <b-form @submit.prevent="submitLogin">
                <b-form-group
                    label="Username"
                    label-for="username">
                    <b-form-input 
                        type="text" 
                        id="username" 
                        placeholder="Username" 
                        v-model="login.username" />
                </b-form-group>
                <b-form-group 
                    label="Password"
                    label-for="password">
                    <b-form-input  
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Password" 
                        v-model="login.password" />
                </b-form-group>
                <b-button type="submit" block>Login</b-button>
            </b-form> 
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
            this.$store.dispatch('user/login', this.login, { root:true });
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