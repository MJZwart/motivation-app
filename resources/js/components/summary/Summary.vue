<template>
    <div>
        <b-card :class="'summary-card ' + headerVariant">
            <template #header>
                <slot name="header">
                    {{title}}
                </slot>
            </template>
            <slot />
            <template v-if="footer" #footer>
                <slot name="footer" class="summary-card-footer" />
            </template>
        </b-card>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: false,
        },
        headerVariant: {
            type:String,
            required: false,
        },
        footer: {
            type: Boolean,
            required: false,
        },
    },
}
</script>

<style lang="scss" scoped>
@import '../../../assets/scss/variables';
.summary-card {

    .card-header {
        color: white;
        background-color: $primary;
        font-weight: 500;
    }
    .card-body {
        padding: 0.5rem;
        font-size: 0.9rem;
    }
    .card-footer {
        padding: 0;
    }
}
.summary-card.light {
    .card-header {
        color: black;
        background-color: $light;
    }
}
// .summary-card-slot {

// }

</style>