export type Field = {
    key: string,
    label: string,
    sortable?: boolean,
    hidden?: boolean,
    sortKey?: string,
}

export type OverviewField = {
    label?: string,
    key?: string,
    fields: Field[],
    width: number,
    sortKey?: string,
}

export type OverviewFieldGroups = {
    fieldGroups: OverviewField[],
    extend?: Field[],
}

export type Item = {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    [key: string]: string | number | unknown | any,
}

export type UserSearch = {
    userSearch: string,
}

export type LanguageOption = {
    key: 'en' | 'nl',
    label: string,
    flag: string,
}

export type Diagnostics = {
    description: string | undefined;
    windowHeight: number;
    windowWidth: number;
}