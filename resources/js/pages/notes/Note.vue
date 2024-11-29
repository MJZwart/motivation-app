<template>
    <div class="flex-row note">
        <Icon :icon="note.completed ? CHECK_SQUARE : CHECK_SQUARE_BLANK" class="complete-note" @click="completeTask" />
        <div class="flex-col w-100" :class="note.completed ? 'completed' : ''">
            <span v-if="!isEditingNote" class="pointer" @click="note.expanded = !note.expanded">
                {{ note.note }}
            </span>
            <span v-else class="flex-row">
                <input v-model="noteTitle" class="note-input" type="text" placeholder="New note" @keyup.enter="updateNoteTitle" />
                <Icon v-if="noteTitle !== ''" :icon="ADD" :style="{fontSize: '36px'}" @click="updateNoteTitle" />
            </span>

            <span v-if="note.expanded" class="silent">
                <span v-if="note.description && !isEditingDescription" :style="{'min-height': '2.5rem'}">
                    {{ note.description }}
                    <Icon :icon="EDIT_PENCIL" @click="editNoteDescription" />
                </span>
                <span v-else>
                    <span class="flex-row">
                        <textarea 
                            v-model="noteDescription" 
                            class="note-input" 
                            type="text" 
                            placeholder="Add description" 
                            rows="1"
                            @keyup.enter="addDescription" />
                        <Icon v-if="noteDescription !== ''" :icon="ADD" :style="{fontSize: '36px'}" @click="addDescription" />
                    </span>
                </span>
            </span>
        </div>
        <span class="ml-auto" :style="{'min-width': '4rem'}">
            <Icon :icon="EDIT_PENCIL" @click="editNote" />
            <Icon :icon="TRASH" class="red" @click="deleteNote" />
        </span>
    </div>
</template>

<script lang="ts" setup>
import {ref} from 'vue';
import {toggleNoteCompleted, updateNote} from './notes';
import {Note} from './types';
import {ADD, CHECK_SQUARE, CHECK_SQUARE_BLANK, EDIT_PENCIL, TRASH} from '/js/constants/iconConstants';
import {deepCopy} from '/js/helpers/copy';

const props = defineProps<{note: Note}>();

const note = ref(deepCopy(props.note));
const noteDescription = ref('');
const noteTitle = ref('');
const isEditingDescription = ref(false);
const isEditingNote = ref(false);

const completeTask = () => {
    toggleNoteCompleted(props.note.id);
}

const addDescription = async () => {
    isEditingDescription.value = true;
    note.value.description = noteDescription.value;
    await updateNote(note.value);
    noteDescription.value = '';
    isEditingDescription.value = false;
}

const deleteNote = async() => {

}

const editNoteDescription = () => {
    noteDescription.value = note.value.description;
    isEditingDescription.value = true;
}

const editNote = () => {
    isEditingNote.value = true;
    noteTitle.value = props.note.note;
}

const updateNoteTitle = async() => {
    note.value.note = noteTitle.value;
    await updateNote(note.value);
    noteTitle.value = '';
    isEditingNote.value = false;
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
.note {
    padding: 0.1rem;
}
.note:hover {
    background-color: rgba(255, 255, 255, 0.04);
    transition: all 0.3s ease-in-out;
}
</style>