<template>
    <div class="w-80 center">
        <BTabs vertical card :options="['pills']">
            <BTab :title="$t('account-settings')" active>
                <AccountSettings />
            </BTab>

            <BTab :title="$t('profile-settings')">
                <ProfileSettings />
            </BTab>

            <BTab :title="$t('reward-settings')">
                <RewardSettings />
            </BTab>
        </BTabs>
    </div>
</template>


<script>
import ProfileSettings from '../components/tabs/settings/ProfileSettings.vue';
import RewardSettings from '../components/tabs/settings/RewardSettings.vue';
import AccountSettings from '../components/tabs/settings/AccountSettings.vue';
import BTabs from '../components/bootstrap/BTabs.vue';
import BTab from '../components/bootstrap/BTab.vue';
export default {
    components: {ProfileSettings, RewardSettings, AccountSettings, BTabs, BTab},
}
</script>