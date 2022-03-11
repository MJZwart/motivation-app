<template>
    <div class="w-80 center">
        <b-tabs card pills vertical>
            <b-tab :title="$t('account-settings')" active>
                <AccountSettings />
            </b-tab>

            <b-tab :title="$t('profile-settings')">
                <ProfileSettings />
            </b-tab>

            <b-tab :title="$t('reward-settings')">
                <RewardSettings />
            </b-tab>
        </b-tabs>
    </div>
</template>


<script>
import ProfileSettings from '../components/tabs/settings/ProfileSettings.vue';
import RewardSettings from '../components/tabs/settings/RewardSettings.vue';
import AccountSettings from '../components/tabs/settings/AccountSettings.vue';

export default {
    components: {ProfileSettings, RewardSettings, AccountSettings},
}
</script>