<template>
    <div>
        <div v-for="(list, idx) in noteLists" :key="idx" class="mb-2 note-list">
            <NoteList :list="list" />
        </div>
        <div>
            <span class="flex flex-row mt-2">
                <input 
                    v-model="newNoteList" 
                    class="note-list-input" 
                    type="text" 
                    placeholder="New list"
                    @keyup.enter="saveNoteList" />
                <Icon v-if="newNoteList !== ''" :icon="ADD" :style="{fontSize: '36px'}" @click="saveNoteList" />
            </span>
        </div>
    </div>
</template>

<script lang="ts" setup>
import {onMounted, ref} from 'vue';
import {createNoteList, fetchNotes, noteLists} from './notes';
import NoteList from './NoteList.vue';
import {ADD} from '/js/constants/iconConstants';

onMounted(() => {
    fetchNotes();
});

const newNoteList = ref('');

const saveNoteList = async() => {
    await createNoteList(newNoteList.value);
    newNoteList.value = '';
}
</script>

<style lang="scss" scoped>
.completed {
    text-decoration-line: line-through;
    opacity: 0.7;
}
.note-list-input {
    max-width: 26rem;
    min-height: 2.5rem;
}
.note-list {
    border-radius: 1rem;
    box-shadow: 0 0.15rem 0.15rem rgb(0 0 0 / 15%);
    background-color: rgba(255, 255, 255, 0.02);
    padding: 0.4rem;
}
</style>