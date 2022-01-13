<template>
    <div>
        <h3>{{ $t('admin-bug-panel') }}</h3>
        <div>
            <h4>{{ "this is an admin bug panel" }}</h4>
        </div>
        <template v-for="bug in bugs">
            <div>
                <p>
                    {{bug.title}} |
                    {{bug.page}} |
                    {{bug.type}} |
                    {{bug.severity}} |
                    {{bug.image_link}} |
                    {{bug.user_id}} |
                    {{bug.comment}} |
                    {{bug.admin_comment}} |
                    {{bug.status}}
                </p>
            </div>
        </template>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
export default {
    mounted() {
        this.$store.dispatch('admin/checkAdmin');
        this.$store.dispatch('bugReport/fetchBugs');
    },
    computed: {
        ...mapGetters({
            bugs: 'bugReport/getBugs',
        }),
    },
    /*data() {
        return {
            notification: {},
        }
    },*/
    methods: {
        /** Sends notification to all members */
        /*sendNotification() {
            this.$store.dispatch('admin/sendNotification', this.notification);
        },*/
    },
    
}
</script>