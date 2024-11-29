<template>
    <span class="flex-row">
        <Icon class="mt-2" :icon="allItemsCompleted ? CHECK_SQUARE : CHECK_SQUARE_BLANK" @click="toggleNoteListCompleted" />
        <span v-if="!isEditing" class="flex-row w-100">
            <h3 :class="allItemsCompleted ? 'completed' : ''" class="pointer" @click="listExpanded = !listExpanded">
                {{ list.title }} ({{amountCompleted}}/{{notes.length}})
            </h3>
            <span class="ml-auto">
                <Icon :icon="EDIT_PENCIL" @click="isEditing = true" />
                <Icon :icon="TRASH" class="red" @click="deleteNoteList(list.id)" />
            </span>
        </span>
        <span v-else class="flex-row">
            <input 
                v-model="editableList.title" 
                class="note-list-input" 
                type="text" 
                placeholder="New list"
                @keyup.enter="updateNoteListTitle" />
            <Icon v-if="editableList.title !== ''" :icon="ADD" :style="{fontSize: '36px'}" @click="updateNoteListTitle" />
        </span>
    </span>
    <div v-if="listExpanded" class="ml-3">
        <div v-for="(item, idxy) in notes" :key="idxy">
            <Note :note="item"/>
        </div>
        <span class="flex-row">
            <input v-model="newNote" class="note-input" type="text" placeholder="New note" @keyup.enter="saveNote" />
            <Icon v-if="newNote !== ''" :icon="ADD" :style="{fontSize: '36px'}" @click="saveNote" />
        </span>
    </div>
</template>

<script lang="ts" setup>
import {computed, ref} from 'vue';
import {createNote, deleteNoteList, getNotesForList, toggleListCompleted, updateNoteList} from './notes';
import {ADD, CHECK_SQUARE, CHECK_SQUARE_BLANK, EDIT_PENCIL, TRASH} from '/js/constants/iconConstants';
import {NoteList} from './types';
import Note from './Note.vue';

const props = defineProps<{list: NoteList}>();

const notes = computed(() => getNotesForList(props.list.id));
const newNote = ref('');
const listExpanded = ref(true);
const editableList = ref({...props.list});
const isEditing = ref(false);

const allItemsCompleted = computed(() => notes.value.length > 0 && notes.value.every(item => item.completed));
const amountCompleted = computed(() => notes.value.filter(item => item.completed).length);


const saveNote = async () => {
    await createNote(newNote.value, props.list.id);
    newNote.value = '';
}

const toggleNoteListCompleted = () => {
    toggleListCompleted(props.list.id);
}

const updateNoteListTitle = () => {
    updateNoteList(editableList.value);
    isEditing.value = false;
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
    min-height: 2.5rem;
}
.note-input:focus {
    color: var(--background-2-text);
}
</style>