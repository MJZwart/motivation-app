<template>
    <div class="tooltip-container">
        <div @mouseover="showTooltip = true" @mouseleave="showTooltip = false">
            <slot />
        </div>
        <div v-show="showTooltip" class="tooltip">
            <span class="text">
                {{text}}
            </span>
        </div>    
    </div>    
</template>

<script>
export default {
    props: {
        text: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            showTooltip: false,
        }
    },
}
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
    z-index: 1;

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
</style>