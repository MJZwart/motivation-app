<template>
    <div class="w-80 center">
        <b-tabs card pills vertical>
            <b-tab :title="$t('account-settings')" active>
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
            </b-tab>

            <b-tab :title="$t('profile-settings')">
                <b-form v-if="!loading" @submit.prevent="submitSettings">
                    <h4>{{ $t('profile-settings') }}</h4>
                    <b-form-group>
                        <b-form-checkbox
                            id="show_reward"
                            v-model="settings.show_reward"
                            name="show_reward"
                            switch>
                            {{ $t('show-reward-on-profile') }}
                        </b-form-checkbox>
                        <base-form-error name="show_reward" /> 
                        <b-form-checkbox
                            id="show_achievements"
                            v-model="settings.show_achievements"
                            name="show_achievements"
                            switch>
                            {{ $t('show-achievements-on-profile') }}
                        </b-form-checkbox>
                        <base-form-error name="show_achievements" /> 
                        <b-form-checkbox
                            id="show_friends"
                            v-model="settings.show_friends"
                            name="show_friends"
                            switch>
                            {{ $t('show-friends-on-profile') }}
                        </b-form-checkbox>
                        <base-form-error name="show_friends" /> 
                    </b-form-group>
                    <b-button type="submit" block>{{ $t('save-profile-settings') }}</b-button>
                </b-form>
            </b-tab>

            <b-tab :title="$t('reward-settings')">
                <h4>{{ $t('reward-settings') }}</h4>
                <p>{{ $t('current-reward-type') }}: {{currentRewardType}}</p>
                <b-button type="button" @click="showChangeRewardType()">{{ $t('change-reward-type') }}</b-button>
            </b-tab>
            
            <change-reward-type v-if="isChangeRewardTypeVisible" :rewardsType="user.rewards" @close="closeChangeRewardType" />
        </b-tabs>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import BaseFormError from '../components/BaseFormError.vue';
import {REWARD_TYPES} from '../constants/rewardConstants';
import ChangeRewardType from '../components/modals/ChangeRewardType.vue';

export default {
    components: {ChangeRewardType, BaseFormError},
    mounted() {
        this.setupSettings();
    },
    data() {
        return {
            /** @type {import('resources/types/task').Task | {}} */
            task: {},
            settings: {},
            emailSettings: {},
            passwordSettings: {},
            loading: true,
            rewardTypes: REWARD_TYPES,
            isChangeRewardTypeVisible: false,
        }
    },
    computed: {
        ...mapGetters({
            user: 'user/getUser',
        }),
        /** @return {string} */
        currentRewardType() {
            return this.user.rewards.toLowerCase();
        },
    },
    methods: {
        /** Sets up the form with the user settings */
        setupSettings() {
            this.emailSettings.email = this.user.email;
            this.settings.show_achievements = this.user.show_achievements;
            this.settings.show_reward = this.user.show_reward;
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
        /** Shows and hides the modal to change the reward type */
        showChangeRewardType() {
            this.isChangeRewardTypeVisible = true;
        },
        closeChangeRewardType() {
            this.isChangeRewardTypeVisible = false;
        },
    },
}
</script>