<template>
    <div>
        <form v-if="!loading" @submit.prevent="submitPasswordSettings">
            <h4>{{ $t('change-password') }}</h4>
            <p class="text-muted">{{ $t('automatically-logged-out') }}</p>
            <div class="form-group">
                <label for="old_password">{{$t('old-password')}}</label>
                <input 
                    id="old_password" 
                    v-model="passwordSettings.old_password"
                    type="password" 
                    name="old_password" 
                    :placeholder="$t('old-password')"  />
                <BaseFormError name="old_password" /> 
            </div>
            <div class="form-group">
                <label for="password">{{$t('new-password')}}</label>
                <input 
                    id="password" 
                    v-model="passwordSettings.password"
                    type="password" 
                    name="password" 
                    :placeholder="$t('new-password')"  />
                <BaseFormError name="password" /> 
            </div>
            <div class="form-group">
                <label for="password_confirmation">{{$t('repeat-new-password')}}</label>
                <input 
                    id="password_confirmation" 
                    v-model="passwordSettings.password_confirmation"
                    type="password" 
                    name="password_confirmation" 
                    :placeholder="$t('repeat-password')"  />
                <BaseFormError name="password_confirmation" /> 
            </div>
            <button type="submit" class="block">{{ $t('update-password') }}</button>
        </form>

        <hr />

        <form v-if="!loading" @submit.prevent="submitEmailSettings">
            <h4>{{ $t('change-email') }}</h4>
            <div class="form-group">
                <label for="email">{{$t('change-email')}}</label>
                <!-- Todo verify e-mail and show e-mail as verified -->
                <input 
                    id="email" 
                    v-model="emailSettings.email"
                    type="text" 
                    name="email" 
                    :placeholder="$t('email')"  />
                <BaseFormError name="email" /> 
            </div>
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