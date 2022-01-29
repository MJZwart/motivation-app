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

export const BUG_SORTABLES = [
    {text: 'Time Created', value: 'TIME_CREATED'},
    {text: 'Title', value: 'TITLE'},
    {text: 'Page', value: 'PAGE'},
    {text: 'Type', value: 'TYPE'},
    {text: 'Severity', value: 'SEVERITY'},
    {text: 'User ID', value: 'USER_ID'},
    {text: 'Status', value: 'STATUS'}];

export const BUG_DEFAULTS = {
    currentSort: 'TIME_CREATED',
    currentSortDir: 'asc',
    currentSortType: 'DESIGN'};