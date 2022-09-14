<template>
    <div>
        <form @submit.prevent="submitSettings">
            <h4>{{ $t('profile-settings') }}</h4>
            <div class="form-group">
                <input id="show_reward" v-model="settings.show_reward" type="checkbox" name="show_reward" />
                <label for="show_reward" class="option-label">{{ $t('show-reward-on-profile') }}</label>
                <BaseFormError name="show_reward" />
                <input
                    id="show_achievements"
                    v-model="settings.show_achievements"
                    type="checkbox"
                    name="show_achievements"
                />
                <label for="show_achievements" class="option-label">{{ $t('show-achievements-on-profile') }}</label>
                <BaseFormError name="show_achievements" />
                <input id="show_friends" v-model="settings.show_friends" type="checkbox" name="show_friends" />
                <label for="show_friends" class="option-label">{{ $t('show-friends-on-profile') }}</label>
                <BaseFormError name="show_friends" />
            </div>
            <button type="submit" class="block">{{ $t('save-profile-settings') }}</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import {ProfileSettings} from 'resources/types/settings';
import {User} from 'resources/types/user';
import {computed, ref} from 'vue';
import {useUserStore} from '/js/store/userStore';
const userStore = useUserStore();

const user = computed<User>(() => userStore.user);

const settings = ref<ProfileSettings>(getUserSettings());

function getUserSettings() {
    return {
        show_achievements: user.value.show_achievements,
        show_reward: user.value.show_reward,
        show_friends: user.value.show_friends,
    };
}

function submitSettings() {
    userStore.updateSettings(settings.value);
}
</script>
