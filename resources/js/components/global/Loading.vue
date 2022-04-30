<template>
    <div class="d-flex justify-content-center">
        <div class="v-pulse v-pulse1" v-bind:style="[spinnerStyle,spinnerDelay1]" />
        <div class="v-pulse v-pulse2" v-bind:style="[spinnerStyle,spinnerDelay2]" />
        <div class="v-pulse v-pulse3" v-bind:style="[spinnerStyle,spinnerDelay3]" />
    </div>
</template>

<script setup>
const props = defineProps({
    color: { 
        type: String,
        default: '#1D3354',
    },
    size: {
        type: String,
        default: '15px',
    },
    margin: {
        type: String,
        default: '2px',
    },
    radius: {
        type: String,
        default: '100%',
    },
});
const spinnerStyle = {
    backgroundColor: props.color,
    width: props.size,
    height: props.size,
    margin: props.margin,
    borderRadius: props.radius,
    display: 'inline-block',
    animationName: 'v-pulseStretchDelay',
    animationDuration: '0.75s',
    animationIterationCount: 'infinite',
    animationTimingFunction: 'cubic-bezier(.2,.68,.18,1.08)',
    animationFillMode: 'both',
};
const spinnerDelay1 = {
    animationDelay: '0.12s',
}
const spinnerDelay2 = {
    animationDelay: '0.24s',
}
const spinnerDelay3 = {
    animationDelay: '0.36s',
}
</script>

<style>
@-webkit-keyframes v-pulseStretchDelay
{
    0%,
    80%
    {
        -webkit-transform: scale(1);
                transform: scale(1);
        -webkit-opacity: 1;             
                opacity: 1;
    }
    45%
    {
        -webkit-transform: scale(0.1);
                transform: scale(0.1);
        -webkit-opacity: 0.7;             
                opacity: 0.7;
    }
}
@keyframes v-pulseStretchDelay
{
    0%,
    80%
    {
        -webkit-transform: scale(1);
                transform: scale(1);
        -webkit-opacity: 1;             
                opacity: 1;
    }
    45%
    {
        -webkit-transform: scale(0.1);
                transform: scale(0.1);
        -webkit-opacity: 0.7;             
                opacity: 0.7;
    }
}
</style>