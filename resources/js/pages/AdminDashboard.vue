<template>
    <div>
        <b-tabs card pills vertical>
            <b-tab title="Achievements">
                <achievements />
            </b-tab>
            <b-tab title="Bug reports">
                Bugs.
            </b-tab>
            <b-tab title="Send notification">
                Notifs.
            </b-tab>
        </b-tabs>
    </div>
</template>


<script>
import Achievements from '../components/tabs/admin/Achievements.vue';

export default {
    components: {
        Achievements,
    },
    mounted() {
        this.$store.dispatch('admin/checkAdmin');
        this.$store.dispatch('achievement/getAllAchievements');
        this.$store.dispatch('achievement/getAchievementTriggers');
    },

}
</script>