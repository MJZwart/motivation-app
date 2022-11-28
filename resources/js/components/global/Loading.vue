<template>
    <span class="justify-content-center" :class="{'d-flex': !button, 'button-spinner': button}">
        <span class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </span>
    </span>
</template>

<script setup>
defineProps({
    button: {
        type: Boolean,
        required: false,
        default: false,
    },
});
</script>

<style lang="scss">
.button-spinner {
    margin-left: 0.5rem;
    .spinner-border {
        width: 1.2rem;
        height: 1.2rem;
        border-width: 0.2em;
        margin-bottom: 0.15rem;
    }
}
.spinner-border {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    vertical-align: text-bottom;
    border: .25em solid currentColor;
    border-right-color: currentcolor;
    border-right-color: transparent;
    border-radius: 50%;
    -webkit-animation: spinner-border .75s linear infinite;
    animation: spinner-border .75s linear infinite;

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
}

@keyframes spinner-border {
    100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
</style>