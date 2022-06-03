<template>
    <FaIcon :icon="['far', 'circle-question']"
            class="icon small question"
            :class="colorVariant"
            @click="openTutorial" />
    <ClearModal :show="tutorialOpen" :item="tutorialItem" class="medium-text" @close="closeTutorial" />
</template>

<script setup>
import {shallowRef, ref} from 'vue';
import TaskList from '../tutorial/TaskList.vue';
const props = defineProps({
    tutorial: {
        type: String,
        required: true,
    },
    colorVariant: {
        type: String,
        required: false,
        default: 'primary',
    },
});
const tutorials = {'TaskList': TaskList};

const tutorialItem = shallowRef({});
const tutorialOpen = ref(false);

function openTutorial() {
    tutorialItem.value = tutorials[props.tutorial];
    tutorialOpen.value = true;
}
function closeTutorial() {
    tutorialOpen.value = false;
}
</script>

<style lang="scss">
.question {
    cursor: help;
}
.question.white {
    color: white;
}
.medium-text {
    b{
        font-size: 1rem;
    }
    font-size: 0.8rem;
}
</style>