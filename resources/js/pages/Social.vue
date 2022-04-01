<template>
    <div>
        <BTabs vertical card :options="['pills']">
            <BTab :title="$t('groups')">
                <Groups/>
            </BTab>
            <BTab :title="$t('friends')">
                <Friends />
            </BTab>
        </BTabs>
    </div>
</template>

<script>
import Groups from '../components/tabs/social/Groups.vue';
import Friends from '../components/tabs/social/Friends.vue';
import BTabs from '../components/bootstrap/BTabs.vue';
import BTab from '../components/bootstrap/BTab.vue';

export default {
    components: {
        Groups, Friends, BTabs, BTab,
    },
}
</script>
