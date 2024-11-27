import {ref} from 'vue';
import {Note, NoteList} from './types';
import axios from 'axios';

const NOTES_API = '/notes';

export const notes = ref<Note[]>([]);
export const noteLists = ref<NoteList[]>([]);

export const fetchNotes = async() => {
    const {data} = await axios.get(NOTES_API);
    notes.value = data.notes;
    noteLists.value = data.noteLists;
}

export const getNotesForList = (noteListId: number): Note[] => {
    return notes.value.filter(note => note.noteListId === noteListId);
}

export const createNoteList = async(noteListTitle: string): Promise<void> => {
    const {data} = await axios.post(NOTES_API + '/note-list', {title: noteListTitle});
    noteLists.value.push(data);
}

export const createNote = async(noteTitle: string, noteListId: number): Promise<void> => {
    const {data} = await axios.post(NOTES_API + '/', {note: noteTitle, note_list_id: noteListId});
    notes.value.push(data.data);
}

export const toggleNoteCompleted = async(noteId: number) => {
    const {data} = await axios.put(NOTES_API + '/complete/' + noteId);
    const idx = notes.value.findIndex(item => item.id === data.data.id);
    notes.value[idx] = data.data;
}

export const toggleListCompleted = async(noteListId: number) => {
    const {data} = await axios.put(NOTES_API + '/complete-list/' + noteListId);
    console.log(data);
    notes.value = data.data;
}