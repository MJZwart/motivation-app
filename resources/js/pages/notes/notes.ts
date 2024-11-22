import {ref} from 'vue';
import {Note, NoteList} from './types';
import axios from 'axios';

export const notes = ref<Note[]>([]);
export const noteLists = ref<NoteList[]>([]);

export const fetchNotes = async() => {
    const {data} = await axios.get('/notes');
    notes.value = data.notes;
    noteLists.value = data.noteLists;
}

export const getNotesForList = (noteList: NoteList): Note[] => {
    return notes.value.filter(note => note.notesListId === noteList.id);
}