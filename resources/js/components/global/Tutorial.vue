<template>
    <Icon  v-if="tutorialActive"
           :icon="QUESTION"
           class="question-icon small question"
           :class="[size]"
           @click="openTutorial" />
    <ClearModal :show="tutorialOpen" :item="tutorialItem" class="medium-text" @close="closeTutorial" />
</template>

<script setup>
import {shallowRef, ref, computed} from 'vue';
import TaskListTutorial from '../tutorial/TaskListTutorial.vue';
import StatsTutorial from '../tutorial/StatsTutorial.vue';
import AchievementsTutorial from '../tutorial/AchievementsTutorial.vue';
import GroupsTutorial from '../tutorial/GroupsTutorial.vue';
import FriendsTutorial from '../tutorial/FriendsTutorial.vue';
import IncomingFriendRequestsTutorial from '../tutorial/IncomingFriendRequestsTutorial.vue';
import OutgoingFriendRequestsTutorial from '../tutorial/OutgoingFriendRequestsTutorial.vue';
import BlocklistTutorial from '../tutorial/BlocklistTutorial.vue';
import CharacterTutorial from '../tutorial/CharacterTutorial.vue';
import VillageTutorial from '../tutorial/VillageTutorial.vue';
import TemplatesTutorial from '../tutorial/TemplatesTutorial.vue';
import {useUserStore} from '/js/store/userStore';
import {QUESTION} from '/js/constants/iconConstants';

const userStore = useUserStore();

const tutorialActive = computed(() => {
    if (!userStore.user) return false;
    return userStore.user.show_tutorial;
});

const props = defineProps({
    tutorial: {
        type: String,
        required: true,
    },
    size: {
        type: String,
        required: false,
        default: 'small',
    },
});
const tutorials = {
    'TaskList': TaskListTutorial,
    'stats': StatsTutorial,
    'achievements': AchievementsTutorial,
    'Groups': GroupsTutorial,
    'Friends': FriendsTutorial,
    'incoming-friend-requests': IncomingFriendRequestsTutorial,
    'outgoing-friend-requests': OutgoingFriendRequestsTutorial,
    'blocklist': BlocklistTutorial,
    'CHARACTER': CharacterTutorial,
    'VILLAGE': VillageTutorial,
    'Templates': TemplatesTutorial,
};

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