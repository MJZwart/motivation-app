<template>
    <div class="card-outer">
        <div class="card summary-card" :class="headerVariant">
            <div class="card-header">
                <slot name="header">
                    <span class="d-flex">
                        {{title}}
                        <Tutorial v-if="!tutorialOff" :tutorial="title" colorVariant="white" />
                    </span>
                </slot>
            </div>
            <div class="card-body" :class="{foot: !footer}">
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
    tutorialOff: {
        type: Boolean,
        required: false,
        default: false,
    },
});
</script>

<style lang="scss" scoped>
.card-outer {
    border-radius: calc(0.25rem + 0px);
    box-shadow: var(--basic-shadow);
    margin: 0.25rem;
}
.summary-card {
    .card-header {
        color: var(--primary)-text;
        background-color: var(--primary);
        font-weight: 500;
    }
    .card-body {
        background-color: var(--background-2);
        color: var(--background-2)-text;
        padding: 0.5rem;
        font-size: 0.9rem;
    }
    .card-body.foot {
        border-radius: 0 0 calc(0.25rem - 1px) calc(0.25rem - 1px);
    }
    .card-footer {
        padding: 0;
        border-radius: 0 0 calc(0.25rem - 1px) calc(0.25rem - 1px);
    }
}
.summary-card.light {
    .card-header {
        color: var(--primary);
        background-color: var(--light);
    }
}
</style>