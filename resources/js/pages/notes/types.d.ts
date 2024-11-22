export interface Note {
    id: number;
    notesListId: number;
    note: string;
    description: string;
    completed: boolean;
}
export interface NoteList {
    id: number;
    title: string;
}