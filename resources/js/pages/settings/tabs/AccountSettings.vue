<template>
    <div>
        <form v-if="!loading" @submit.prevent="submitPasswordSettings">
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
            <button type="submit" class="block">{{ $t('update-password') }}</button>
        </form>

        <hr />

        <form v-if="!loading" @submit.prevent="submitEmailSettings">
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
            <button type="submit" class="block">{{ $t('update-email') }}</button>
        </form>

        <hr />
        
        <h4>{{$t('language')}}</h4>
        <ChangeLanguage class="large" />
    </div>
</template>

<script setup lang="ts">
import {onMounted, ref, computed} from 'vue';
import {useUserStore} from '/js/store/userStore';
import type {PasswordSettings, EmailSettings} from 'resources/types/settings';
import ChangeLanguage from '../../../components/global/ChangeLanguage.vue';

const userStore = useUserStore();

const user = computed(() => userStore.user);

onMounted(() => setupSettings());

const emailSettings = ref<EmailSettings>({
    email: user.value.email.slice(),
});
const passwordSettings = ref<PasswordSettings>({
    old_password: '',
    password: '',
    password_confirmation: '',
});
const loading = ref(true);

/** Sets up the form with the user settings */
function setupSettings() {
    emailSettings.value.email = user.value.email;
    loading.value = false;
}

function submitPasswordSettings() {
    userStore.updatePassword(passwordSettings.value);
}
function submitEmailSettings() {
    userStore.updateEmail(emailSettings.value.email);
    // TODO doesn't update the page
}
</script>