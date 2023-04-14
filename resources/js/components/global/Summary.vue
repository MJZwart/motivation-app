<template>
    <div class="card-outer">
        <div class="card summary-card" :class="headerVariant">
            <div class="card-header" :class="{collapse: collapse}" @click="toggleCollapse">
                <slot name="header">
                    <span class="d-flex">
                        {{title}}
                        <Tutorial v-if="!tutorialOff" :tutorial="title" />
                        <Icon v-if="collapse" class="ml-auto primary-text" :icon="expanded ? ARROW_UP : ARROW_DOWN" />
                    </span>
                </slot>
            </div>
            <div class="card-body-wrapper" :class="{expanded: expanded}">
                <div class="card-body" :class="{foot: !footer}">
                    <slot />
                </div>
            </div>
            <div v-if="footer" class="card-footer">
                <slot name="footer" class="summary-card-footer" />
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {ARROW_DOWN, ARROW_UP} from '/js/constants/iconConstants';

const props = defineProps({
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
    collapse: {
        type: Boolean,
        required: false,
        default: false,
    },
});
const expanded = ref(true);
function toggleCollapse() {
    if (!props.collapse) return;
    expanded.value = !expanded.value;

}
</script>

<style lang="scss" scoped>
.card-outer {
    border-radius: calc(0.25rem + 0px);
    box-shadow: var(--basic-shadow);
    margin: 0.25rem;
}
.summary-card {
    .card-header {
        color: var(--primary-text);
        background-color: var(--primary);
        font-weight: 500;
    }
    .card-header.collapse {
        cursor: pointer;
    }
    .card-body {
        background-color: var(--background-2);
        color: var(--background-2-text);
        padding: 0.5rem;
        font-size: 0.9rem;
    }
    .card-body-wrapper {
        padding: 0;
        max-height: 0px;
        transition: max-height 0.3s ease-in-out;
    }
    .card-body-wrapper.expanded {
        max-height: 100rem;
        transition: max-height 0.3s ease-in-out;
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