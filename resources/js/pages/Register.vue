<template>
    <div>
        <h2>Register</h2>

        <div class="register-form">
            <form @submit.prevent="submitRegister">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Username" 
                        v-model="register.username" />
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input 
                        type="text" 
                        id="email" 
                        name="email" 
                        placeholder="E-mail" 
                        v-model="register.email" />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Password" 
                        v-model="register.password" />
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Repeat password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="Repeat password" 
                        v-model="register.password_confirmation" />
                </div>
                <div class="form-group">
                    <button type="submit" class="long-button">Register new account</button>
                </div>
            </form> 
        </div>
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