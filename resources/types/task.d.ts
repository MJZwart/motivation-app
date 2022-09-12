export type Task = NewTask & {
    id: number;
    tasks?: Task[];
};

export type NewTask = {
    name: string;
    description: string;
    difficulty: number;
    type: string;
    repeatable: string;
    super_task_id?: number | null;
    task_list_id: number;
};

export type TaskList = NewTaskList & {
    id: number;
    color?: string | null;
    tasks: Task[];
};

export type NewTaskList = {
    name: string;
}