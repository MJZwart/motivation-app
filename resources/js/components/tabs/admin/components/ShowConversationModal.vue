<template>
    <div>
        <Loading v-if="loading" />
        <template v-else>
            <ShowMessage v-for="message in conversation.messages" :key="message.id"
                         :message="message" />
        </template>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import ShowMessage from './ShowMessage.vue';
import Loading from '../../../Loading.vue'
export default {
    components: {
        ShowMessage, Loading,
    },
    props: {
        conversationId: {
            type: Number,
            required: true,
        },
    },
    computed: {
        ...mapGetters({
            conversation: 'admin/getConversation',
        }),
    },
    data() {
        return {
            loading: true,
        }
    },
    mounted() {
        this.$store.dispatch('admin/fetchConversation', this.conversationId).then(() => this.loading = false);
    },
}
</script>