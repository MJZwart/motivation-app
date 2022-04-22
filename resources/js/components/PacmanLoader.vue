<template>
    <div v-show="loading" class="v-spinner" v-bind:style="{position: 'relative', fontSize: 0}">
        <div class="v-pacman v-pacman1" v-bind:style="spinnerStyle1">x</div>
        <div class="v-pacman v-pacman2" v-bind:style="[spinnerStyle,animationStyle,spinnerDelay2]">x</div>
        <div class="v-pacman v-pacman3" v-bind:style="[spinnerStyle,animationStyle,spinnerDelay3]">x</div>
        <div class="v-pacman v-pacman4" v-bind:style="[spinnerStyle,animationStyle,spinnerDelay4]">x</div>
        <div class="v-pacman v-pacman5" v-bind:style="[spinnerStyle,animationStyle,spinnerDelay5]">x</div>
    </div>
</template>

<script setup>
import {computed} from 'vue';
const props = defineProps({
    loading: {
        type: Boolean,
        default: true,
    },
    color: { 
        type: String,
        default: '#1D3354',
    },
    size: {
        type: String,
        default: '25px',
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
const spinnerDelay2 = {
    animationDelay: '0.25s',
}
const spinnerDelay3 = {
    animationDelay: '0.50s',
}
const spinnerDelay4 = {
    animationDelay: '0.75s',
}
const spinnerDelay5 = {
    animationDelay: '1s',
}
const spinnerStyle = computed(() => {
    return {
        backgroundColor: props.color,
        width: props.size,
        height: props.size,
        margin: props.margin,
        borderRadius: props.radius,
    }
});
const spinnerStyle1 = computed(() => {
    const border1 = props.size + ' solid transparent';
    const border2 = props.size + ' solid ' + props.color;
    return {
        width: 0,
        height: 0,
        borderTop: border2,
        borderRight: border1,
        borderBottom: border2,
        borderLeft: border2,
        borderRadius: props.size,
    }
});
const animationStyle = computed(() => {
    return {
        width: '10px',
        height: '10px',
        transform: 'translate(0, '+ -parseFloat(props.size)/4 + 'px)',
        position: 'absolute',
        top: '25px',
        left: '100px',
        animationName: 'v-pacmanStretchDelay',
        animationDuration: '1s',
        animationIterationCount: 'infinite',
        animationTimingFunction: 'linear',
        animationFillMode: 'both',
    }
});
</script>

<style>
.v-spinner
{
    text-align: center;
}
/*TODO computed transform */
@-webkit-keyframes v-pacmanStretchDelay
{
    75%
    {
        -webkit-opacity: 0.7;             
        opacity: 0.7;
    }
    100%
    {
        -webkit-transform: translate(-100px, -6.25px);
                transform: translate(-100px, -6.25px);
    }
}
@keyframes v-pacmanStretchDelay
{
    75%
    {
        -webkit-opacity: 0.7;             
        opacity: 0.7;
    }
    100%
    {
        -webkit-transform: translate(-100px, -6.25px);
                transform: translate(-100px, -6.25px);
    }
}
</style>