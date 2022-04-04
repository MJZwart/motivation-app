<template>
    <div>
        <form v-if="!loading" @submit.prevent="submitPasswordSettings">
            <h4>{{ $t('change-password') }}</h4>
            <p class="text-muted">{{ $t('automatically-logged-out') }}</p>
            <div class="form-group">
                <label for="old_password">{{$t('old-password')}}</label>
                <input 
                    id="old_password" 
                    v-model="passwordSettings.old_password"
                    type="password" 
                    name="old_password" 
                    :placeholder="$t('old-password')"  />
                <base-form-error name="old_password" /> 
            </div>
            <div class="form-group">
                <label for="password">{{$t('new-password')}}</label>
                <input 
                    id="password" 
                    v-model="passwordSettings.password"
                    type="password" 
                    name="password" 
                    :placeholder="$t('new-password')"  />
                <base-form-error name="password" /> 
            </div>
            <div class="form-group">
                <label for="password_confirmation">{{$t('repeat-new-password')}}</label>
                <input 
                    id="password_confirmation" 
                    v-model="passwordSettings.password_confirmation"
                    type="password" 
                    name="password_confirmation" 
                    :placeholder="$t('repeat-password')"  />
                <base-form-error name="password_confirmation" /> 
            </div>
            <b-button type="submit" block>{{ $t('update-password') }}</b-button>
        </form>

        <hr />

        <form v-if="!loading" @submit.prevent="submitEmailSettings">
            <h4>{{ $t('change-email') }}</h4>
            <div class="form-group">
                <label for="email">{{$t('change-email')}}</label>
                <!-- Todo verify e-mail and show e-mail as verified -->
                <input 
                    id="email" 
                    v-model="emailSettings.email"
                    type="text" 
                    name="email" 
                    :placeholder="$t('email')"  />
                <base-form-error name="email" /> 
            </div>
            <b-button type="submit" block>{{ $t('update-email') }}</b-button>
        </form>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import BaseFormError from '../../BaseFormError.vue';

export default {
    components: {BaseFormError},
    mounted() {
        this.setupSettings();
    },
    data() {
        return {
            emailSettings: {},
            passwordSettings: {},
            loading: true,
        }
    },
    computed: {
        ...mapGetters({
            user: 'user/getUser',
        }),
    },
    methods: {
        /** Sets up the form with the user settings */
        setupSettings() {
            this.emailSettings.email = this.user.email;
            this.loading = false;
        },
        submitPasswordSettings() {
            this.$store.dispatch('user/updatePassword', this.passwordSettings);
        },
        submitEmailSettings() {
            this.$store.dispatch('user/updateEmail', this.emailSettings);
        },
    },
}
</script>