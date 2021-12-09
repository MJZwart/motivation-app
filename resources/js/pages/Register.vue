<template>
    <div class="w-40 center">
        <h2>{{ $t('register') }}</h2>
        <b-form @submit.prevent="submitRegister">
            <b-form-group
                :label="$t('username')"
                label-for="username">
                <b-form-input 
                    type="text" 
                    id="username" 
                    name="username" 
                    :placeholder="$t('username')" 
                    v-model="register.username" />
                <base-form-error name="username" /> 
            </b-form-group>
            <b-form-group
                :label="$t('email')"
                label-for="email">
                <b-form-input 
                    type="text" 
                    id="email" 
                    name="email" 
                    :placeholder="$t('email')" 
                    v-model="register.email" />
                <base-form-error name="email" /> 
            </b-form-group>
            <b-form-group
                :label="$t('password')"
                label-for="password">
                <b-form-input 
                    type="password" 
                    id="password" 
                    name="password" 
                    :placeholder="$t('password')" 
                    v-model="register.password" />
                <base-form-error name="password" /> 
            </b-form-group>
            <b-form-group
                :label="$t('repeat-password')"
                label-for="password_confirmation">
                <b-form-input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    :placeholder="$t('repeat-password')" 
                    v-model="register.password_confirmation" />
                <base-form-error name="password_confirmation" /> 
            </b-form-group>
            <b-button type="submit" block>{{ $t('register-new-account') }}</b-button>
        </b-form> 
    </div>
</template>


<script>
import BaseFormError from '../components/BaseFormError.vue';

export default {
    components: {
        BaseFormError
    },
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