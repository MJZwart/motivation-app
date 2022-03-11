<template>
    <div>
        <b-form v-if="!loading" @submit.prevent="submitPasswordSettings">
            <h4>{{ $t('change-password') }}</h4>
            <p class="text-muted">{{ $t('automatically-logged-out') }}</p>
            <b-form-group
                :label="$t('old-password')"
                label-for="old_password">
                <b-form-input 
                    id="old_password" 
                    v-model="passwordSettings.old_password"
                    type="password" 
                    name="old_password" 
                    :placeholder="$t('old-password')"  />
                <base-form-error name="old_password" /> 
            </b-form-group>
            <b-form-group
                :label="$t('new-password')"
                label-for="password">
                <b-form-input 
                    id="password" 
                    v-model="passwordSettings.password"
                    type="password" 
                    name="password" 
                    :placeholder="$t('new-password')"  />
                <base-form-error name="password" /> 
            </b-form-group>
            <b-form-group
                :label="$t('repeat-new-password')"
                label-for="password_confirmation">
                <b-form-input 
                    id="password_confirmation" 
                    v-model="passwordSettings.password_confirmation"
                    type="password" 
                    name="password_confirmation" 
                    :placeholder="$t('repeat-password')"  />
                <base-form-error name="password_confirmation" /> 
            </b-form-group>
            <b-button type="submit" block>{{ $t('update-password') }}</b-button>
        </b-form>

        <hr />

        <b-form v-if="!loading" @submit.prevent="submitEmailSettings">
            <h4>{{ $t('change-email') }}</h4>
            <b-form-group
                :label="$t('change-email')"
                label-for="email">
                <!-- Todo verify e-mail and show e-mail as verified -->
                <b-form-input 
                    id="email" 
                    v-model="emailSettings.email"
                    type="text" 
                    name="email" 
                    :placeholder="$t('email')"  />
                <base-form-error name="email" /> 
            </b-form-group>
            <b-button type="submit" block>{{ $t('update-email') }}</b-button>
        </b-form>
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