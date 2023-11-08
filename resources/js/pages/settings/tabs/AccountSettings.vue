<template>
    <div v-if="loading || !user" />
    <div v-else>
        <div v-if="!isGuest">
            <form @submit.prevent="submitPasswordSettings">
                <h4>{{ $t('change-password') }}</h4>
                <p class="text-muted">{{ $t('automatically-logged-out') }}</p>
                <SimpleInput
                    id="old_password"
                    v-model="passwordSettings.old_password"
                    type="password"
                    name="old_password"
                    :label="$t('old-password')"
                    :placeholder="$t('old-password')"
                />
                <SimpleInput
                    id="password"
                    v-model="passwordSettings.password"
                    type="password"
                    name="password"
                    :label="$t('new-password')"
                    :placeholder="$t('new-password')"
                />
                <SimpleInput
                    id="password_confirmation"
                    v-model="passwordSettings.password_confirmation"
                    type="password"
                    name="password_confirmation"
                    :label="$t('repeat-new-password')"
                    :placeholder="$t('repeat-password')"
                />
                <SubmitButton class="block">{{ $t('update-password') }}</SubmitButton>
            </form>

            <hr />

            <form @submit.prevent="submitEmailSettings">
                <h4>{{ $t('change-email') }}</h4>
                <!-- Todo verify e-mail and show e-mail as verified -->
                <SimpleInput
                    id="email"
                    v-model="emailSettings.email"
                    type="text"
                    name="email"
                    :label="$t('change-email')"
                    :placeholder="$t('email')"
                />
                <SubmitButton class="block">{{ $t('update-email') }}</SubmitButton>
            </form>
        </div>
        
        <div v-else>
            <form @submit.prevent="submitUpgradeGuestAccount">
                <h4>{{ $t('upgrade-guest-account') }}</h4>
                <p class="text-muted">{{ $t('upgrade-guest-account-subtext') }}</p>
                <SimpleInput
                    id="username"
                    v-model="accountCredentials.username"
                    type="text"
                    name="username"
                    :label="$t('username')"
                    :placeholder="$t('username')"
                />
                <SimpleInput 
                    id="email" 
                    v-model="accountCredentials.email"
                    type="text" 
                    name="email" 
                    :label="$t('email')"
                    :placeholder="$t('email')"  />
                <SimpleInput
                    id="password"
                    v-model="accountCredentials.password"
                    type="password"
                    name="password"
                    :label="$t('new-password')"
                    :placeholder="$t('new-password')"
                />
                <SimpleInput
                    id="password_confirmation"
                    v-model="accountCredentials.password_confirmation"
                    type="password"
                    name="password_confirmation"
                    :label="$t('repeat-new-password')"
                    :placeholder="$t('repeat-password')"
                />
                <div class="form-group">
                    <SimpleCheckbox 
                        id="agree_to_tos" 
                        v-model="accountCredentials.agree_to_tos" 
                        name="agree_to_tos" />
                    <label 
                        for="agree_to_tos" 
                        class="pointer" 
                        @click="accountCredentials.agree_to_tos = !accountCredentials.agree_to_tos">
                        {{ $t('agree-to-tos-pre') }}
                        <router-link to="/tos" target="_blank">{{ $t('tos') }}</router-link>
                    </label>
                    <BaseFormError name="agree_to_tos" />
                </div>
                <SubmitButton class="block">{{ $t('upgrade-guest-account') }}</SubmitButton>
            </form>
        </div>
        
        <hr />

        <div>
            <h4>{{ $t('show-or-hide-tutorials') }}</h4>
            <ToggleButton 
                :active="user.show_tutorial"
                @click="toggleShowTutorial">
                {{ user.show_tutorial ? $t('hide-tutorials') : $t('show-tutorials') }}
            </ToggleButton>
        </div>

        <hr />

        <div>
            <h4>{{$t('language')}}</h4>
            <ChangeLanguage class="large" />
        </div>

        <hr />

        <div>
            <h4>{{ $t('toggle-dark-mode') }}</h4>
            <ToggleButton 
                :active="currentTheme === 'light'"
                @click="toggleDarkMode">
                {{ currentTheme === 'dark' ? $t('light-mode') : $t('dark-mode') }}
            </ToggleButton>
        </div>
    </div>
</template>

<script setup lang="ts">
import {onMounted, ref, computed} from 'vue';
import {useUserStore} from '/js/store/userStore';
import ToggleButton from '/js/components/global/ToggleButton.vue';
import type {PasswordSettings, EmailSettings} from 'resources/types/settings';
import ChangeLanguage from '../../../components/global/ChangeLanguage.vue';
import {currentTheme, setCurrentTheme} from '/js/services/themeService';
import {Register} from 'resources/types/user';

const userStore = useUserStore();

const user = computed(() => userStore.user);
const isGuest = computed(() => userStore.user ? userStore.user.guest : true);

const accountCredentials = ref<Register>({
    username: user.value?.username ?? '',
    email: '',
    password: '',
    password_confirmation: '',
    agree_to_tos: false,
});

onMounted(() => setupSettings());

const emailSettings = ref<EmailSettings>({
    email: user.value && user.value.email ? user.value.email.slice() : '',
});
const passwordSettings = ref<PasswordSettings>({
    old_password: '',
    password: '',
    password_confirmation: '',
});
const loading = ref(true);

/** Sets up the form with the user settings */
function setupSettings() {
    emailSettings.value.email = user.value?.email ?? '';
    loading.value = false;
}

function submitPasswordSettings() {
    userStore.updatePassword(passwordSettings.value);
}
function submitEmailSettings() {
    userStore.updateEmail(emailSettings.value);
    // TODO doesn't update the page
}
function toggleShowTutorial() {
    if (!user.value) return;
    userStore.toggleTutorial({show: !user.value.show_tutorial});
}
function toggleDarkMode() {
    currentTheme.value === 'dark' ? setCurrentTheme('light', true) : setCurrentTheme('dark', true);
}

function submitUpgradeGuestAccount() {
    if (!user.value) return;
    userStore.upgradeGuestAccount(accountCredentials.value, user.value?.id);
}
</script>
