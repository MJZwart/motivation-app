<template>
    <div>
        <div v-for="(list, idx) in noteLists" :key="idx">
            <span>{{ list.title }}</span>
            <div>
                <span v-for="(item, idxy) in getNotesForList(list)" :key="idxy">{{ item.note }}</span>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import {onMounted} from 'vue';
import {fetchNotes, getNotesForList, noteLists} from './notes';

onMounted(() => {
    console.log('Going to fetchg')
    fetchNotes();
});



</script>