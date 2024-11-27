<template>
    <span class="flex flex-row">
        <Icon class="mt-2" :icon="allItemsCompleted ? CHECK_SQUARE : CHECK_SQUARE_BLANK" @click="toggleNoteListCompleted" />
        <h3 :class="allItemsCompleted ? 'completed' : ''" class="pointer" @click="listExpanded = !listExpanded">
            {{ list.title }} ({{amountCompleted}}/{{notes.length}})
        </h3>
    </span>
    <div v-if="listExpanded" class="ml-3">
        <div v-for="(item, idxy) in notes" :key="idxy" class="flex flex-row">
            <Icon :icon="item.completed ? CHECK_SQUARE : CHECK_SQUARE_BLANK" class="complete-note" @click="completeTask(item)" />
            <div class="flex-col" :class="item.completed ? 'completed' : ''">
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
import {createNote, getNotesForList, toggleListCompleted, toggleNoteCompleted} from './notes';
import {ADD, CHECK_SQUARE, CHECK_SQUARE_BLANK} from '/js/constants/iconConstants';
import {Note, NoteList} from './types';

const props = defineProps<{list: NoteList}>();

const notes = computed(() => getNotesForList(props.list.id));
const newNote = ref('');
const listExpanded = ref(true);

const allItemsCompleted = computed(() => notes.value.every(item => item.completed));
const amountCompleted = computed(() => notes.value.filter(item => item.completed).length);

const completeTask = (item: Note) => {
    toggleNoteCompleted(item.id);
}

const saveNote = async () => {
    await createNote(newNote.value, props.list.id);
    newNote.value = '';
}

const toggleNoteListCompleted = () => {
    toggleListCompleted(props.list.id);
}
</script>

<style lang="scss" scoped>
.completed {
    text-decoration-line: line-through;
    opacity: 0.7;
}
.complete-note {
    min-width: 1.6rem;
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