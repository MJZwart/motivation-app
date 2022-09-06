export type Field = {
    key: string,
    label: string,
    sortable?: boolean,
}

export type Item = {
    [key: string]: string | number | unknown,
}

export type UserSearch = {
    userSearch: string,
}