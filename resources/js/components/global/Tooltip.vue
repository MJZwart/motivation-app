<template>
    <div class="tooltip-container">
        <div @mouseover="showTooltip = true" @mouseleave="showTooltip = false">
            <slot />
        </div>
        <div v-show="showTooltip" class="tooltip" :class="tooltipPosition">
            <span class="text">
                {{text}}
            </span>
        </div>    
    </div>    
</template>

<script setup>
import {ref, computed} from 'vue';
const props = defineProps({
    text: {
        type: String,
        required: true,
    },
    placement: {
        type: String,
        required: false,
        default: 'top',
    },
});

const tooltipPosition = computed(() => props.placement);

const showTooltip = ref(false);
</script>

<style lang="scss" scoped>
.tooltip-container { 
    position: relative;
    display: inline-block;
}

.tooltip { 
    color: #ffffff;
    text-align: center;
    border-radius: 4px;

    bottom: 130%;
    left:50%; 
    transform:translateX(-50%);
    width: max-content;
    opacity: 0.9;
    padding: 5px 10px;
    transition: opacity 0.5s;

    position: absolute;
    z-index: 99999;

    background: #000000;
}

.text::after {
    content: " ";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #000000 transparent transparent transparent;

}

.tooltip.right {
    left: 0;
    bottom: 0;
    transform: translateX(20%) translateY(10%);
    .text::after {
        top: 37%;
        left: -4%;
        margin-top: 0px;
        border-color: transparent #000000 transparent transparent;
    }
}
.tooltip.left {
    left: 0;
    bottom: 0;
    transform: translateX(-103%);
    .text::after {
        top: 37%;
        left: 100%;
        margin-left: 0px;
        border-color: transparent transparent transparent #000000;
    }
}
.tooltip.bottom {
    left: 50%;
    bottom: -35px;
    transform: translateX(-50%);
    .text::after {
        top: -30%;
        left: 50%;
        margin-left: -5px;
        border-color: transparent transparent #000000 transparent;
    }
}
</style>