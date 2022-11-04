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
.toggle-button {
    background-color: var(--background-2);
    border: none;
    border-radius: 0.3rem;
    box-shadow: var(--basic-shadow);
    color: var(--background-2)-text;
    font-family: var(--border-color);
    transition: all 0.2s ease-in-out;
}
.toggle-button:hover {
    background-color: var(--background-darker);
    transition: all 0.3s ease-in-out;
}
.power-button {
    margin-right: 0.5rem;
    color: var(--green);
}
.power-button.active {
    color: var(--red);
}
</style>