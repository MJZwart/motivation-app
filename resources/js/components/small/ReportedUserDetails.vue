
<template>
    <div>
        <b-table
            :items="user.reports"
            :fields="reportedUserDetailsFields"
            class="reported-user-details-table"
            striped
        >
            <template #cell(actions)>
                <b-button @click="sendMessageToReportedUser">place holder message</b-button>
            </template>
        </b-table>

        <b-modal id="send-message-to-reported-user" hide-footer :title="'placeholer title'">
            <SendMessage :user="user" @close="closeSendMessageToReportedUser"/>
        </b-modal>
    </div>
</template>


<script>
import {REPORTED_USER_DETAILS_FIELDS} from '../../constants/reportedUserConstants.js';
import SendMessage from '../modals/SendMessage.vue';
export default {
    components: {
        SendMessage,
    },
    props: {
        user: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            reportedUserDetailsFields: REPORTED_USER_DETAILS_FIELDS,
        }
    },
    methods: {
        sendMessageToReportedUser() {
            this.$store.dispatch('clearErrors');
            this.$bvModal.show('send-message-to-reported-user');
        },
        closeSendMessageToReportedUser() {
            this.$bvModal.show('send-message-to-reported-user');
        },
    },
}
</script>
