export type Task = {
    id: number;
    description: string;
    difficulty: number;
    name: string;
    repeatable: string; //TODO Enum
    super_task: number | null;
    task_list_id: number;
    tasks: Task[];
    type: number;
};

export type TaskList = {
    id: number;
    color: string | null;
    name: string;
    tasks: Task[];
};
