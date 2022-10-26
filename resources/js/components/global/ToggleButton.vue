<template>
    <button
        class="toggle-button"
        :class="{active: active}">
        <FaIcon 
            icon="fa-power-off" 
            class="power-button"
            :class="{active: active}" />
        <slot />
    </button>
</template>

<script setup lang="ts">
defineProps<{active: boolean}>();
</script>


<style lang="scss" scoped>
@import '../../../assets/scss/variables';
.toggle-button {
    background-color: $background-2;
    border: none;
    border-radius: 0.3rem;
    box-shadow: $basic-shadow;
    color: $background-2-text;
    font-family: $bold-font;
    transition: all 0.2s ease-in-out;
}
.toggle-button:hover {
    background-color: $background-darker;
    transition: all 0.3s ease-in-out;
}
.power-button {
    margin-right: 0.5rem;
    color: $green;
}
.power-button.active {
    color: $red;
}
</style>