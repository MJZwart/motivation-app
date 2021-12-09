<template>
    <div class="w-40 center">
        <h2>{{ $t('login') }}</h2>
        <b-form @submit.prevent="submitLogin">
            <b-form-group
                :label="$t('username')"
                label-for="username">
                <b-form-input 
                    type="text" 
                    id="username" 
                    :placeholder="$t('username')" 
                    v-model="login.username" />
                <base-form-error name="username" /> 
            </b-form-group>
            <b-form-group 
                :label="$t('password')"
                label-for="password">
                <b-form-input  
                    type="password" 
                    id="password" 
                    name="password" 
                    :placeholder="$t('password')" 
                    v-model="login.password" />
                <base-form-error name="password" /> 
            </b-form-group>
            <b-button type="submit" block>{{ $t('login') }}</b-button>
            <base-form-error name="error" /> 
        </b-form> 
    </div>
</template>


<script>
import BaseFormError from '../components/BaseFormError.vue';

export default {
    components: {
        BaseFormError,
    },
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