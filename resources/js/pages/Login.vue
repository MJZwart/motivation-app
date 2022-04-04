<template>
    <div class="w-40 center">
        <h2>{{ $t('login') }}</h2>
        <form @submit.prevent="submitLogin">
            <div class="form-group">
                <label label-for="username">{{$t('username')}}</label>
                <input 
                    id="username" 
                    v-model="login.username" 
                    type="text" 
                    :placeholder="$t('username')" />
                <base-form-error name="username" /> 
            </div>
            <div class="form-group">
                <label label-for="password">{{$t('password')}}</label>
                <input  
                    id="password" 
                    v-model="login.password"
                    type="password" 
                    name="password" 
                    :placeholder="$t('password')" />
                <base-form-error name="password" /> 
            </div>
            <button type="submit" class="block">{{ $t('login') }}</button>
            <base-form-error name="error" /> 
        </form> 
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