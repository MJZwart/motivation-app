import {Field, OverviewField} from 'resources/types/global';

export const ALL_GROUP_FIELDS: Field[] = [
    {
        label: 'Name', 
        key: 'name',
        sortable: true,
    },
    {
        label: 'Description', 
        key: 'description',
    },
    {
        label: 'Details',
        key: 'details',
    },
];

export const MY_GROUP_FIELDS: Field[] = [
    {
        label: 'Name', 
        key: 'name',
        sortable: true,
    },
    {
        label: 'Description', 
        key: 'description',
    },
    {
        label: 'Member since', 
        key: 'joined',
        sortable: true,
    },
    {
        label: 'Rank', 
        key: 'rank',
    },
    {
        label: 'Details',
        key: 'details',
    },
];

export const ALL_GROUP_FIELDS_OVERVIEW: OverviewField[] = [
    {
        key: 'nameField',
        fields: [
            {
                label: 'Name', 
                key: 'name',
                sortable: true,
                hidden: true,
            },
            {
                label: 'Description', 
                key: 'description',
            },
        ],
        width: 9,
    },
    {
        fields: [
            {
                label: 'Details',
                key: 'details',
            },
        ],
        width: 1,
    },
];

export const MY_GROUP_FIELDS_OVERVIEW: OverviewField[] = [
    {
        key: 'nameField',
        fields: [
            {
                label: 'Name', 
                key: 'name',
                sortable: true,
                hidden: true,
            },
            {
                label: 'Description', 
                key: 'description',
            },
        ],
        width: 6,
    },
    {
        key: 'personal',
        fields: [
            {
                label: 'Member since', 
                key: 'joined',
                sortable: true,
            },
            {
                label: 'Rank', 
                key: 'rank',
            },
        ],
        width: 3,
    },
    {
        fields: [
            {
                label: 'Details',
                key: 'details',
            },
        ],
        width: 1,
    },
];