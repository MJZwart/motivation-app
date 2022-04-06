<template>
    <div v-show="loading" class="v-spinner" v-bind:style="{position: 'relative', fontSize: 0}">
        <div class="v-pacman v-pacman1" v-bind:style="spinnerStyle1">x</div>
        <div class="v-pacman v-pacman2" v-bind:style="[spinnerStyle,animationStyle,spinnerDelay2]">x</div>
        <div class="v-pacman v-pacman3" v-bind:style="[spinnerStyle,animationStyle,spinnerDelay3]">x</div>
        <div class="v-pacman v-pacman4" v-bind:style="[spinnerStyle,animationStyle,spinnerDelay4]">x</div>
        <div class="v-pacman v-pacman5" v-bind:style="[spinnerStyle,animationStyle,spinnerDelay5]">x</div>
    </div>
    <!-- <div class="d-flex justify-content-center">
        <b-spinner variant="primary" label="Loading..." />
    </div> -->
</template>

<script>
export default {
    props: {
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
    },
    data () {
        return {
            spinnerDelay2: {
                animationDelay: '0.25s',
            },
            spinnerDelay3: {
                animationDelay: '0.50s',
            },
            spinnerDelay4: {
                animationDelay: '0.75s',
            },
            spinnerDelay5: {
                animationDelay: '1s',
            },
        }
    },
    computed: {
        spinnerStyle () {
            return {
                backgroundColor: this.color,
                width: this.size,
                height: this.size,
                margin: this.margin,
                borderRadius: this.radius,
            }
        },
        spinnerStyle1 () {
            const border1 = this.size + ' solid transparent';
            const border2 = this.size + ' solid ' + this.color;
            return {
                width: 0,
                height: 0,
                borderTop: border2,
                borderRight: border1,
                borderBottom: border2,
                borderLeft: border2,
                borderRadius: this.size,
            }
        },
        animationStyle () {
            return {
                width: '10px',
                height: '10px',
                transform: 'translate(0, '+ -parseFloat(this.size)/4 + 'px)',
                position: 'absolute',
                top: '25px',
                left: '100px',
                animationName: 'v-pacmanStretchDelay',
                animationDuration: '1s',
                animationIterationCount: 'infinite',
                animationTimingFunction: 'linear',
                animationFillMode: 'both',
            }
        },
    },
}
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