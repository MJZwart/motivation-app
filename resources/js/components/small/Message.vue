<template>
    <div v-if="message" class="break-word"
         @mouseover="showActionButtons = true"
         @mouseleave="showActionButtons = false">
        <p class="mb-0">{{getSender}} {{message.message}}</p>
        <p class="silent d-flex">
            {{message.created_at}}
            <span v-if="showActionButtons" class="ml-auto">                
                <b-icon-trash
                    class="icon-small red"
                    @click="deleteMessage()" />
            </span>
        </p>
    </div>
</template>

<script>

export default {
    props: {
        message: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            showActionButtons: false,
        }
    },
    computed: {
        getSender() {
            return this.message.sent_by_user ? this.$t('you')+': ' : this.message.sender.username + ': ';
        },
    },
    methods: {
        deleteMessage() {
            this.$emit('deleteMessage', this.message);
        },
    },
}
</script>
