<template>
    <div>
        <Summary class="mb-2">
            <template #header>
                <span class="d-flex">
                    {{notification.title}}
                    <span class="ml-auto">
                        <span v-if="!notification.read">{{ $t('new') }} </span>
                        <FaIcon 
                            icon="fa-solid fa-trash"
                            class="icon small red"
                            @click="deleteNotification()" />
                    </span>
                </span>
            </template>
            <slot>
                <p>{{notification.text}}</p>
                <p>{{ $t('received-on') }}: {{notification.created_at}}</p>
            </slot>
        </Summary>
    </div>
</template>


<script>
import Summary from '../summary/Summary.vue';
export default {
    components: {Summary},
    props: {
        notification: {
            /** @type {import('resources/types/notification').Notification} */
            type: Object,
            required: true,
        },
    },
    methods: {
        deleteNotification() {
            if (confirm(this.$t('delete-notification-confirmation'))) {
                this.$store.dispatch('notification/deleteNotification', this.notification.id);
            }
        },
    },
}
</script>


<style>
</style>