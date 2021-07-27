<template>
    <div>
        <div class="form-title">
            <h3>Settings</h3>
        </div>
        <form @submit.prevent="submitPasswordSettings" v-if="user">
            <h3>Change password</h3>
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
                <button type="submit" class="long-button">Save password</button>
            </div>
        </form>

        <hr />

        <form @submit.prevent="submitSettings" v-if="user">
            <h3>Other settings</h3>
            <div class="form-group">
                <label for="email">Change e-mail</label>
                <!-- Todo verify e-mail and show e-mail as verified -->
                <input 
                    type="text" 
                    id="email" 
                    name="email" 
                    placeholder="E-mail" 
                    v-model="settings.email" />
            </div><div class="form-group">
                <label for="full_display_name">Full display name</label>
                <input 
                    type="text" 
                    id="full_display_name" 
                    name="full_display_name" 
                    placeholder="full_display_name" 
                    v-model="settings.full_display_name" />
            </div>
            <div class="form-group">
                <label for="rewards">Rewards type</label>
                <select
                    name="rewards"
                    id="rewards"
                    v-model="settings.rewards">
                    <option value="CHARACTER">Character</option>
                </select>
            </div>
            <div class="form-group">
                <label for="show_character">Profile settings</label>
                <b-form-checkbox
                    name="show_character"
                    id="show_character"
                    v-model="settings.show_character"
                    switch>Show character on profile
                </b-form-checkbox>
                <b-form-checkbox
                    name="show_achievements"
                    id="show_achievements"
                    v-model="settings.show_achievements"
                    switch>
                    Show achievements on profile
                </b-form-checkbox>
                <b-form-checkbox
                    name="show_friends"
                    id="show_friends"
                    v-model="settings.show_friends"
                    switch>
                    Show friends on profile
                </b-form-checkbox>
            </div>

            <div class="form-group">
                <button type="submit" class="long-button">Save settings</button>
            </div>
        </form>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
export default {
    mounted() {
        this.settings = this.user;
    },
    data() {
        return {
            task: {},
            settings: {},
            passwordSettings: {},
        }
    },
    computed: {
        ...mapGetters({
            user: 'user/getUser',
        }),
    },
    methods: {
        submitPasswordSettings() {
            
        },
        submitSettings() {
            
        },
    },
}
</script>


<style>

</style>