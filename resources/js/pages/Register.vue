<template>
    <div>
        <h2>Register</h2>
        <b-form @submit.prevent="submitRegister">
            <b-form-group
                label="Username"
                label-for="username">
                <b-form-input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Username" 
                    v-model="register.username" />
            </b-form-group>
            <b-form-group
                label="E-mail"
                label-for="email">
                <b-form-input 
                    type="text" 
                    id="email" 
                    name="email" 
                    placeholder="E-mail" 
                    v-model="register.email" />
            </b-form-group>
            <b-form-group
                label="Password"
                label-for="password">
                <b-form-input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Password" 
                    v-model="register.password" />
            </b-form-group>
            <b-form-group
                label="Repeat password"
                label-for="password_confirmation">
                <b-form-input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Repeat password" 
                    v-model="register.password_confirmation" />
            </b-form-group>
            <b-button type="submit" block>Register new account</b-button>
        </b-form> 
    </div>
</template>


<script>
export default {
    data() {
        return {
            register: {
                username: '',
                email: '',
                password: '',
                password_confirmation: '',
            },
        }
    },
    methods: {
        submitRegister(){
            this.$store.dispatch('user/register', this.register);
        }
    },
    
}
</script>


<style>
    .register-form{
        width:40%;
        margin: auto auto;
    }
</style>