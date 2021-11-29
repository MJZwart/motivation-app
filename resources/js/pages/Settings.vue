<template>
    <div class="w-60 center">
        <div class="form-title">
            <h3>Settings</h3>
        </div>
        <b-form @submit.prevent="submitSettings" v-if="!loading">
            <h4>Profile settings</h4>
            <b-form-group
                label="Rewards type"
                label-for="rewards">
                <b-form-select
                    name="rewards"
                    id="rewards"
                    v-model="settings.rewards"
                    :options="rewardTypes">
                </b-form-select>
                <base-form-error name="rewards" /> 
            </b-form-group>
            <b-form-group>
                <b-form-checkbox
                    name="show_character"
                    id="show_character"
                    v-model="settings.show_character"
                    switch>
                    Show character on profile
                </b-form-checkbox>
                <base-form-error name="show_character" /> 
                <b-form-checkbox
                    name="show_achievements"
                    id="show_achievements"
                    v-model="settings.show_achievements"
                    switch>
                    Show achievements on profile
                </b-form-checkbox>
                <base-form-error name="show_achievements" /> 
                <b-form-checkbox
                    name="show_friends"
                    id="show_friends"
                    v-model="settings.show_friends"
                    switch>
                    Show friends on profile
                </b-form-checkbox>
                <base-form-error name="show_friends" /> 
            </b-form-group>

            <div class="form-group">
                <b-button type="submit" block>Save settings</b-button>
            </div>
        </b-form>
        <hr />
        <form @submit.prevent="submitPasswordSettings" v-if="!loading">
            <h4>Change password</h4>
            <p class="text-muted">If successful, you will be logged out. Please log in with your new password.</p>
            <b-form-group
                label="Old password"
                label-for="old_password">
                <b-form-input 
                    type="password" 
                    id="old_password" 
                    name="old_password" 
                    placeholder="Old password" 
                    v-model="passwordSettings.old_password" />
                <base-form-error name="old_password" /> 
            </b-form-group>
            <b-form-group
                label="New password"
                label-for="password">
                <b-form-input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="New password" 
                    v-model="passwordSettings.password" />
                <base-form-error name="password" /> 
            </b-form-group>
            <b-form-group
                label="Repeat new password"
                label-for="password_confirmation">
                <b-form-input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Repeat password" 
                    v-model="passwordSettings.password_confirmation" />
                <base-form-error name="password_confirmation" /> 
            </b-form-group>
            
            <div class="form-group">
                <b-button type="submit" block>Update password</b-button>
            </div>
        </form>

        <hr />

        <form @submit.prevent="submitEmailSettings" v-if="!loading">
            <h4>Change e-mail</h4>
            <b-form-group
                label="Change e-mail"
                label-for="email">
                <!-- Todo verify e-mail and show e-mail as verified -->
                <b-form-input 
                    type="text" 
                    id="email" 
                    name="email" 
                    placeholder="E-mail" 
                    v-model="emailSettings.email" />
                <base-form-error name="email" /> 
            </b-form-group>
            <div class="form-group">
                <b-button type="submit" block>Update e-mail</b-button>
            </div>
        </form>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import BaseFormError from '../components/BaseFormError';
import { REWARD_TYPES } from '../constants/rewardConstants';

export default {
    components: {
        BaseFormError
    },
    mounted() {
        this.setupSettings();
    },
    data() {
        return {
            task: {},
            settings: {},
            emailSettings: {},
            passwordSettings: {},
            loading: true,
            rewardTypes: REWARD_TYPES,
        }
    },
    computed: {
        ...mapGetters({
            user: 'user/getUser',
        }),
    },
    methods: {
        setupSettings() {
            this.emailSettings.email = this.user.email;
            this.settings.rewards = this.user.rewards;
            this.settings.show_achievements = this.user.show_achievements;
            this.settings.show_character = this.user.show_character;
            this.settings.show_friends = this.user.show_friends;
            this.loading = false;
        },
        submitPasswordSettings() {
            this.$store.dispatch('user/updatePassword', this.passwordSettings);
        },
        submitSettings() {
            this.$store.dispatch('user/updateSettings', this.settings);
        },
        submitEmailSettings() {
            this.$store.dispatch('user/updateEmail', this.emailSettings);
        },
    },
}
</script>