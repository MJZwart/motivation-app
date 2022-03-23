<template>
    <div>
        <template v-if="conversation">
            <AdminShowMessage v-for="message in conversation.messages" :key="message.id"
                              :message="message" />
        </template>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import AdminShowMessage from '../small/AdminShowMessage.vue';
export default {
    components: {
        AdminShowMessage,
    },
    props: {
        conversationId: {
            required: true,
        },
    },
    computed: {
        ...mapGetters({
            conversation: 'admin/getConversation',
        }),
    },
    mounted() {
        this.$store.dispatch('admin/fetchConversation', this.conversationId);
    },
}
</script>