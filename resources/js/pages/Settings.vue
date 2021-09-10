<template>
    <div>
        <div class="form-title">
            <h3>Settings</h3>
        </div>

        <form @submit.prevent="submitSettings" v-if="!loading">
            <h4>Profile settings</h4>
            <div class="form-group">
                <p>Current reward type: {{currentRewardType}}</p>
                <button type="button" class="long-button" @click="showChangeRewardType()">Change reward type</button>
            </div>
            <div class="form-group">
                <b-form-checkbox
                    name="show_character"
                    id="show_character"
                    v-model="settings.show_character"
                    >
                    Show character on profile
                </b-form-checkbox>
                <b-form-checkbox
                    name="show_achievements"
                    id="show_achievements"
                    v-model="settings.show_achievements"
                    >
                    Show achievements on profile
                </b-form-checkbox>
                <b-form-checkbox
                    name="show_friends"
                    id="show_friends"
                    v-model="settings.show_friends"
                    >
                    Show friends on profile
                </b-form-checkbox>
            </div>

            <div class="form-group">
                <button type="submit" class="long-button">Save settings</button>
            </div>
        </form>

        <hr />
        <form @submit.prevent="submitPasswordSettings" v-if="!loading">
            <h4>Change password</h4>
            <p class="silent">If successful, you will be logged out. Please log in with your new password.</p>
            <div class="form-group">
                <label for="old_password">Old password</label>
                <input 
                    type="password" 
                    id="old_password" 
                    name="old_password" 
                    placeholder="Old password" 
                    v-model="passwordSettings.old_password" />
            </div>
            <div class="form-group">
                <label for="password">New password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="New password" 
                    v-model="passwordSettings.password" />
            </div>
            <div class="form-group">
                <label for="password_confirmation">Repeat new password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Repeat password" 
                    v-model="passwordSettings.password_confirmation" />
            </div>
            
            <div class="form-group">
                <button type="submit" class="long-button">Update password</button>
            </div>
        </form>

        <hr />

        <form @submit.prevent="submitEmailSettings" v-if="!loading">
            <h4>Change e-mail</h4>
            <div class="form-group">
                <label for="email">Change e-mail</label>
                <!-- Todo verify e-mail and show e-mail as verified -->
                <input 
                    type="text" 
                    id="email" 
                    name="email" 
                    placeholder="E-mail" 
                    v-model="emailSettings.email" />
            </div>
            <div class="form-group">
                <button type="submit" class="long-button">Update e-mail</button>
            </div>
        </form>

        <change-reward-type v-if="isChangeRewardTypeVisible" @close="closeChangeRewardType" :rewardsType="user.rewards"></change-reward-type>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import ChangeRewardType from '../components/modals/ChangeRewardType.vue';
export default {
  components: { ChangeRewardType },
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
            isChangeRewardTypeVisible: false,
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
            this.isChangeRewardTypeVisible = true;
        },
        closeChangeRewardType(){
            this.isChangeRewardTypeVisible = false;
        },
    },
}
</script>


<style>

</style>