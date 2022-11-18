<template>
    <div>
        {{parsedCoins.gold}}
        <span v-if="parsedCoins.gold" class="dot gold" />
        {{parsedCoins.silver}}
        <span v-if="parsedCoins.silver" class="dot silver" />
        {{parsedCoins.bronze}}
        <span v-if="parsedCoins.bronze" class="dot bronze" />
    </div>
</template>

<script setup lang="ts">
import {computed} from 'vue';
import type {Coins} from 'resources/types/reward';

const props = defineProps<{coins: number}>();

const parsedCoins = computed<Coins>(() => parseCoins(props.coins));


function parseCoins(coins: number): Coins
{
    const coinString = coins.toString();
    const coinObject = {
        bronze: coinString.slice(-2),
        silver: coinString.slice(-4, -2),
        gold: coinString.slice(0, -4),
    };
    return coinObject;
}
</script>

<style lang="scss" scoped>
.dot {
    height: 12px;
    width: 12px;
    border-radius: 50%;
    display: inline-block;
}
.dot.gold {
    border: 1px solid #976e06;
    background-color: #ca9205;
}
.dot.silver {    
    border: 1px solid #8b8b8b;
    background-color: silver;
}
.dot.bronze {
    border: 1px solid #6b3913;
    background-color: #8b4b1a;
}
</style>