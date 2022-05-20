export const BUG_SEVERITY = [
    {text: 'Low (Not too important)', value: 1}, 
    {text: 'Medium (A bit odd/annoying)', value: 2},
    {text: 'High (Quite limiting)', value: 3}, 
    {text: 'Severe (Very limiting)', value: 4}, 
    {text: 'Critical (Site-breaking)', value: 5}];

export const BUG_TYPES = [
    {text: 'Design', value: 'DESIGN'}, 
    {text: 'Language', value: 'LANGUAGE'},
    {text: 'Functionality', value: 'FUNCTIONALITY'}, 
    {text: 'Other', value: 'OTHER'}];

export const BUG_REPORT_OVERVIEW_FIELDS = [
    {label: 'Time Created', key: 'time_created', sortable: true},
    {label: 'Title', key: 'title', sortable: true},
    {label: 'Page', key: 'page', sortable: true},
    {label: 'Type', key: 'type', sortable: true},
    {label: 'Severity', key: 'severity', sortable: true},
    {label: 'User', key: 'user'},
    {label: 'Status', key: 'status', sortable: true},
    {label: 'Image', key: 'image'},
    {label: 'Comment', key: 'comment'},
    {label: 'Admin comment', key: 'admin_comment'},
    {label: 'Actions', key: 'actions'},
];

export const BUG_STATUS = [
    {text: 'Reported', value: 0},
    {text: 'In progress', value: 1},
    {text: 'Done', value: 2},
    {text: 'Resolved', value: 3}];

export const BUG_DEFAULTS = {
    currentSort: 'time_created',
    currentSortDir: 'desc',
    currentSortType: 'DESIGN'};