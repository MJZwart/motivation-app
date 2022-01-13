export type Task = {
    id: number,
    description: String,
    difficulty: number,
    name: String,
    repeatable: String, //TODO Enum
    super_task: number | null,
    task_list_id: number,
    tasks: Array<Task>,
    type: number,
}

export type TaskList = {
    id: number,
    color: String | null,
    name: String,
    tasks: Array<Task>,
}