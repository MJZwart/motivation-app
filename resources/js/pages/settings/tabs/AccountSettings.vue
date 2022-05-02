<template>
    <div>
        <form v-if="!loading" @submit.prevent="submitPasswordSettings">
            <h4>{{ $t('change-password') }}</h4>
            <p class="text-muted">{{ $t('automatically-logged-out') }}</p>
            <Input 
                id="old_password" 
                v-model="passwordSettings.old_password"
                type="password" 
                name="old_password" 
                :label="$t('old-password')"
                :placeholder="$t('old-password')"  />
            <Input 
                id="password" 
                v-model="passwordSettings.password"
                type="password" 
                name="password" 
                :label="$t('new-password')"
                :placeholder="$t('new-password')"  />
            <Input 
                id="password_confirmation" 
                v-model="passwordSettings.password_confirmation"
                type="password" 
                name="password_confirmation" 
                :label="$t('repeat-new-password')"
                :placeholder="$t('repeat-password')"  />
            <button type="submit" class="block">{{ $t('update-password') }}</button>
        </form>

        <hr />

        <form v-if="!loading" @submit.prevent="submitEmailSettings">
            <h4>{{ $t('change-email') }}</h4>
            <!-- Todo verify e-mail and show e-mail as verified -->
            <Input 
                id="email" 
                v-model="emailSettings.email"
                type="text" 
                name="email" 
                :label="$t('change-email')"
                :placeholder="$t('email')"  />
            <button type="submit" class="block">{{ $t('update-email') }}</button>
        </form>
    </div>
</template>

<script setup>
import {onMounted, ref, computed} from 'vue';
import {useUserStore} from '/js/store/userStore';
const userStore = useUserStore();

onMounted(() => setupSettings());

const emailSettings = ref({});
const passwordSettings = ref({});
const loading = ref(true);

const user = computed(() => userStore.user);

/** Sets up the form with the user settings */
function setupSettings() {
    emailSettings.value.email = user.value.email;
    loading.value = false;
}
function submitPasswordSettings() {
    userStore.updatePassword(passwordSettings.value);
}
function submitEmailSettings() {
    userStore.updateEmail(emailSettings.value);
}
</script>