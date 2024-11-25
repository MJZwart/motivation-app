<template>
    <span class="flex flex-row">
        <Icon class="mt-2" :icon="allItemsCompleted ? CHECK_SQUARE : CHECK_SQUARE_BLANK" />
        <h3>{{ list.title }}</h3>
    </span>
    <div class="ml-3">
        <div v-for="(item, idxy) in notes" :key="idxy" class="flex flex-row">
            <Icon :icon="item.completed ? CHECK_SQUARE : CHECK_SQUARE_BLANK" @click="completeTask(item)" />
            <div class="flex-col">
                <span class="pointer" @click="item.expanded = !item.expanded">{{ item.note }}</span>
                <span v-if="item.expanded" class="silent">{{ item.description }}</span>
            </div>
        </div>
        <span class="flex flex-row">
            <input v-model="newNote" class="note-input" type="text" placeholder="New note" @keyup.enter="saveNote" />
            <Icon :icon="ADD" :style="{fontSize: '36px'}" @click="saveNote" />
        </span>
    </div>
</template>

<script lang="ts" setup>
import {computed, ref} from 'vue';
import {createNote, getNotesForList} from './notes';
import {ADD, CHECK_SQUARE, CHECK_SQUARE_BLANK} from '/js/constants/iconConstants';
import {Note, NoteList} from './types';

const props = defineProps<{list: NoteList}>();

const notes = ref(getNotesForList(props.list.id));
const newNote = ref('');

const allItemsCompleted = computed(() => notes.value.every(item => item.completed));

const completeTask = (item: Note) => {
    console.log('In progress, completing note', item);
}

const saveNote = () => {
    console.log('In progress, saving new note');
    createNote(newNote.value, props.list.id);
    // Save note in back-end
    // Add note to local
    // notes.value.push({})
    newNote.value = '';
}
</script>

<style lang="scss" scoped>
.completed {
    text-decoration-line: line-through;
    opacity: 0.7;
}
.note-input {
    background-color: transparent;
    border-top: none;
    border-left: none;
    border-right: none;
    border-radius: 0;
    border-bottom: 2px solid var(--background-2-text);
    max-width: 24rem;
}
.note-input:focus {
    color: var(--background-2-text);
}
</style>