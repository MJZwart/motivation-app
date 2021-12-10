<template>
    <div class="w-60 center">
        <h3>{{ $t('settings') }}</h3>
        <b-form @submit.prevent="submitSettings" v-if="!loading">
            <h4>{{ $t('profile-settings') }}</h4>
            <b-form-group>
                <p>{{ $t('current-reward-type') }}: {{currentRewardType}}</p>
                <b-button type="button" @click="showChangeRewardType()">{{ $t('change-reward-type') }}</b-button>
            </b-form-group>
            <b-form-group>
                <b-form-checkbox
                    name="show_character"
                    id="show_character"
                    v-model="settings.show_character"
                    switch>
                    {{ $t('show-character-on-profile') }}
                </b-form-checkbox>
                <base-form-error name="show_character" /> 
                <b-form-checkbox
                    name="show_achievements"
                    id="show_achievements"
                    v-model="settings.show_achievements"
                    switch>
                    {{ $t('show-achievements-on-profile') }}
                </b-form-checkbox>
                <base-form-error name="show_achievements" /> 
                <b-form-checkbox
                    name="show_friends"
                    id="show_friends"
                    v-model="settings.show_friends"
                    switch>
                    {{ $t('show-friends-on-profile') }}
                </b-form-checkbox>
                <base-form-error name="show_friends" /> 
            </b-form-group>
            <b-button type="submit" block>{{ $t('save-settings') }}</b-button>
        </b-form>

        <hr />
        
        <b-form @submit.prevent="submitPasswordSettings" v-if="!loading">
            <h4>{{ $t('change-password') }}</h4>
            <p class="text-muted">{{ $t('automatically-logged-out') }}</p>
            <b-form-group
                :label="$t('old-password')"
                label-for="old_password">
                <b-form-input 
                    type="password" 
                    id="old_password" 
                    name="old_password" 
                    :placeholder="$t('old-password')" 
                    v-model="passwordSettings.old_password" />
                <base-form-error name="old_password" /> 
            </b-form-group>
            <b-form-group
                :label="$t('new-password')"
                label-for="password">
                <b-form-input 
                    type="password" 
                    id="password" 
                    name="password" 
                    :placeholder="$t('new-password')" 
                    v-model="passwordSettings.password" />
                <base-form-error name="password" /> 
            </b-form-group>
            <b-form-group
                :label="$t('repeat-new-password')"
                label-for="password_confirmation">
                <b-form-input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    :placeholder="$t('repeat-password')" 
                    v-model="passwordSettings.password_confirmation" />
                <base-form-error name="password_confirmation" /> 
            </b-form-group>
            <b-button type="submit" block>{{ $t('update-password') }}</b-button>
        </b-form>

        <hr />

        <b-form @submit.prevent="submitEmailSettings" v-if="!loading">
            <h4>{{ $t('change-email') }}</h4>
            <b-form-group
                :label="$t('change-email')"
                label-for="email">
                <!-- Todo verify e-mail and show e-mail as verified -->
                <b-form-input 
                    type="text" 
                    id="email" 
                    name="email" 
                    :placeholder="$t('email')" 
                    v-model="emailSettings.email" />
                <base-form-error name="email" /> 
            </b-form-group>
            <b-button type="submit" block>{{ $t('update-email') }}</b-button>
        </b-form>

    <b-modal id="change-reward-type" hide-footer :title="$t('change-reward-type')">
        <change-reward-type @close="closeChangeRewardType" :rewardsType="user.rewards"></change-reward-type>
    </b-modal>
        
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import BaseFormError from '../components/BaseFormError';
import { REWARD_TYPES } from '../constants/rewardConstants';
import ChangeRewardType from '../components/modals/ChangeRewardType.vue';

export default {
  components: { ChangeRewardType, BaseFormError},
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
        currentRewardType(){
            return this.user.rewards.toLowerCase();
        },
    },
    methods: {
        setupSettings() {
            this.emailSettings.email = this.user.email;
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
        showChangeRewardType(){
            this.$bvModal.show('change-reward-type');
        },
        closeChangeRewardType(){
            this.$bvModal.hide('change-reward-type');
        },
    },
}
</script>