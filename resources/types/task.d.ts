export type Task = NewTask & {
    id: number;
    tasks?: Task[];
};

export type NewTask = {
    name: string;
    description?: string;
    difficulty: number;
    type: string;
    repeatable: string;
    repeatable_reset_day?: number;
    repeatable_reset_days?: number[];
    super_task_id?: number | null;
    task_list_id: number;
};

export type TaskList = NewTaskList & {
    id: number;
    color?: string | null;
};

export type NewTaskList = {
    name: string;
};

export type Template = NewTemplate & {
    id: number;
};

export type NewTemplate = {
    name: string;
    description?: string;
    difficulty: number;
    type: string;
}