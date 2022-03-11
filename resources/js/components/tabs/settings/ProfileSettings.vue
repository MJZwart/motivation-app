<template>
    <div>
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
            settings: {},
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
            this.settings.show_achievements = this.user.show_achievements;
            this.settings.show_reward = this.user.show_reward;
            this.settings.show_friends = this.user.show_friends;
            this.loading = false;
        },
        submitSettings() {
            this.$store.dispatch('user/updateSettings', this.settings);
        },
    },
}
</script>