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

export const getNotesForList = (noteListId: number): Note[] => {
    return notes.value.filter(note => note.noteListId === noteListId);
}

export const createNoteList = async(noteListTitle: string) => {
    const {data} = await axios.post('/notes/note-list', {title: noteListTitle});
    console.log(data)
    noteLists.value.push(data);
}

export const createNote = async(noteTitle: string, noteListId: number) => {
    const {data} = await axios.post('/notes/', {note: noteTitle, note_list_id: noteListId});
    console.log(data)
    return data;
}