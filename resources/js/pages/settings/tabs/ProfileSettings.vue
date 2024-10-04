<template>
    <div>
        <form @submit.prevent="submitSettings">
            <h4>{{ $t('profile-settings') }}</h4>
            <div class="form-group">
                <SimpleFormCheckbox 
                    id="show-reward"
                    v-model="settings.show_reward" 
                    name="show_reward" 
                    :label="$t('show-reward-on-profile')" />
                <SimpleFormCheckbox 
                    id="show-achievements"
                    v-model="settings.show_achievements" 
                    name="show_achievements" 
                    :label="$t('show-achievements-on-profile')" />
                <SimpleFormCheckbox 
                    id="show-friends"
                    v-model="settings.show_friends" 
                    name="show_friends" 
                    :label="$t('show-friends-on-profile')" />
                <SimpleFormCheckbox 
                    id="show-timeline"
                    v-model="settings.show_timeline" 
                    name="show_timeline" 
                    :label="$t('show-timeline-on-profile')" />
            </div>
            <SubmitButton class="block">{{ $t('save-profile-settings') }}</SubmitButton>
        </form>
    </div>
</template>

<script setup lang="ts">
import {ProfileSettings} from 'resources/types/settings';
import {ref} from 'vue';
import axios from 'axios';
import { setUser, user } from '/js/services/userService';

const settings = ref<ProfileSettings>(getUserSettings());

function getUserSettings() {
    return {
        show_achievements: user.value?.show_achievements ?? true,
        show_reward: user.value?.show_reward ?? true,
        show_friends: user.value?.show_friends ?? true,
        show_timeline: user.value?.show_timeline ?? false,
    };
}

async function submitSettings() {
    const {data} = await axios.put('/user/settings', settings.value);
    setUser(data.data.user);
}
</script>
