<template>
    <div>
        <h3>{{ $t('admin-bug-panel') }}</h3>
        <div>
            <h4>{{ "this is an admin bug panel" }}</h4>
        </div>
        <template v-for="bug in bugs">
            <div :key="bug.id" :title="bug.title" class="bug">
                <div :class="headerColour(bug.severity) + ' d-flex header'">
                    <span>{{bug.title}}</span>
                    <span class="m-auto">{{bug.type}}</span>
                    <span class="ml-auto">{{bug.severity}}</span>
                </div>
                <div class="bug-content">
                    <p>Page: {{bug.page}}</p>
                    <p v-if="bug.image_link">Image: {{bug.image_link}}</p>
                    <p>Comment: "{{bug.comment}}"</p>
                    <p>Reported by user: {{bug.user_id}} </p>
                    <p>Status: {{bug.status}}</p>
                    <span v-if="bug.admin_comment">Admin comment: "{{bug.admin_comment}}"</span>
                </div>
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
        headerColour(severity) {
            return 'severity-' + severity;
        },
        /** Sends notification to all members */
        /*sendNotification() {
            this.$store.dispatch('admin/sendNotification', this.notification);
        },*/
    },

    
}
</script>

<style lang="scss" scoped>

//NOTE: Parsing the severity in the back-end will break the colours. Change the 'severity-#' to 'severity-low' etc
@import '../../assets/scss/variables';
.bug{
    border: 1px solid $grey;
    color: black;
    margin-bottom: 0.5rem;
    .header {
        padding: 0.2rem;
    }
    .bug-content {
        p {
            margin-bottom: 0.2rem;
        }
    }
    .severity-1 {
        background-color: $green;
    }
    .severity-2 {
        background-color: $yellow;
    }
    .severity-3 {
        background-color: $orange;
    }
    .severity-4 {
        color: white;
        background-color: $red;
    }
    .severity-5 {
        color: white;
        background-color: $darkred;
    }  
}

</style>