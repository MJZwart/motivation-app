<template>
    <div>
        <form v-if="!loading" @submit.prevent="submitSettings">
            <h4>{{ $t('profile-settings') }}</h4>
            <div class="form-group">
                <input
                    id="show_reward"
                    v-model="settings.show_reward"
                    type="checkbox"
                    name="show_reward" />
                <label for="show_reward">{{ $t('show-reward-on-profile') }}</label>
                <base-form-error name="show_reward" /> 
                <input
                    id="show_achievements"
                    v-model="settings.show_achievements"
                    type="checkbox"
                    name="show_achievements" />
                <label for="show_achievements">{{ $t('show-achievements-on-profile') }}</label>
                <base-form-error name="show_achievements" /> 
                <input
                    id="show_friends"
                    v-model="settings.show_friends"
                    type="checkbox"
                    name="show_friends" />
                <label for="show_friends">{{ $t('show-friends-on-profile') }}</label>
                <base-form-error name="show_friends" /> 
            </div>
            <b-button type="submit" block>{{ $t('save-profile-settings') }}</b-button>
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