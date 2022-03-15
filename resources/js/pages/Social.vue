<template>
    <div>
        <b-tabs vertical pills card>
            <b-tab :title="$t('groups')">
                <Groups/>
            </b-tab>
            <b-tab :title="$t('friends')">
                <Friends />
            </b-tab>
        </b-tabs>
    </div>
</template>

<script>
import Groups from '../components/Groups.vue';
import Friends from '../components/Friends.vue';

export default {
    components: {
        Groups, Friends,
    },
}
</script>
