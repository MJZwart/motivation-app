<template>
    <div class="w-40 center">
        <h2>{{ $t('login') }}</h2>
        <b-form @submit.prevent="submitLogin">
            <b-form-group
                :label="$t('username')"
                label-for="username">
                <b-form-input 
                    id="username" 
                    v-model="login.username" 
                    type="text" 
                    :placeholder="$t('username')"  />
                <base-form-error name="username" /> 
            </b-form-group>
            <b-form-group 
                :label="$t('password')"
                label-for="password">
                <b-form-input  
                    id="password" 
                    v-model="login.password"
                    type="password" 
                    name="password" 
                    :placeholder="$t('password')" />
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
    data() {
        return {
            login: {
                username: '',
                password: '',
            },
        }
    },
    methods: {
        submitLogin() {
            this.$store.dispatch('user/login', this.login, {root:true});
        },
    },
}
</script>