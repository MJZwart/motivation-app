export type Field = {
    key: string,
    label: string,
    sortable?: boolean,
    hidden?: boolean,
}

export type OverviewField = {
    label?: string,
    key?: string,
    fields: Field[],
    width: number,
}

export type OverviewFieldGroups = {
    fieldGroups: OverviewField[],
    extend?: Field[],
}

export type Item = {
    [key: string]: string | number | unknown,
}

export type UserSearch = {
    userSearch: string,
}