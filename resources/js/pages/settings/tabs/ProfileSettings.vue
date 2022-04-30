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
                <label for="show_reward" class="option-label">{{ $t('show-reward-on-profile') }}</label>
                <base-form-error name="show_reward" /> 
                <input
                    id="show_achievements"
                    v-model="settings.show_achievements"
                    type="checkbox"
                    name="show_achievements" />
                <label for="show_achievements" class="option-label">{{ $t('show-achievements-on-profile') }}</label>
                <base-form-error name="show_achievements" /> 
                <input
                    id="show_friends"
                    v-model="settings.show_friends"
                    type="checkbox"
                    name="show_friends" />
                <label for="show_friends" class="option-label">{{ $t('show-friends-on-profile') }}</label>
                <base-form-error name="show_friends" /> 
            </div>
            <button type="submit" class="block">{{ $t('save-profile-settings') }}</button>
        </form>
    </div>
</template>

<script setup>
import {onMounted, computed, ref} from 'vue';
import {useUserStore} from '/js/store/userStore';
const userStore = useUserStore();

onMounted(() => setupSettings()); 

const settings = ref({});
const loading = ref(true);

const user = computed(() => userStore.user);

/** Sets up the form with the user settings */
function setupSettings() {
    settings.value.show_achievements = user.value.show_achievements;
    settings.value.show_reward = user.value.show_reward;
    settings.value.show_friends = user.value.show_friends;
    loading.value = false;
}
function submitSettings() {
    userStore.updateSettings(settings.value);
}

</script>