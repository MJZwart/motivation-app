<template>
    <div>
        <div class="card summary-card" :class="headerVariant">
            <div class="card-header">
                <slot name="header">
                    {{title}}
                </slot>
            </div>
            <div class="card-body">
                <slot />
            </div>
            <div v-if="footer" class="card-footer">
                <slot name="footer" class="summary-card-footer" />
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: false,
    },
    headerVariant: {
        type:String,
        required: false,
        default: '',
    },
    footer: {
        type: Boolean,
        required: false,
    },
});
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
</style>