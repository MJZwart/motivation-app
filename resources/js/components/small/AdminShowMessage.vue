<template>
    <div v-if="message" class="break_work">
        <p class="mb-0">{{`${message.sender_username}: ${message.message}`}}</p>
        <p class="silent d-flex">
            {{message.created_at}}
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
}
</script>
