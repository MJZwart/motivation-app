<template>
    <div>
        <div v-for="(list, idx) in noteLists" :key="idx" class="mb-2">
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
                <Icon :icon="ADD" :style="{fontSize: '36px'}" @click="saveNoteList" />
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
.note-card {
    border-radius:0.25rem;
    // padding: 1rem;
    // background-color: var(--background-2);
}
.note-list-input {
    max-width: 26rem;
}
</style>