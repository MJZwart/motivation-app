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
    {text: 'Time Created', value: 'time_created'},
    {text: 'Title', value: 'title'},
    {text: 'Page', value: 'page'},
    {text: 'Type', value: 'type'},
    {text: 'Severity', value: 'severity'},
    {text: 'User ID', value: 'user_id'},
    {text: 'Status', value: 'status'}];

export const BUG_DEFAULTS = {
    currentSort: 'time_created',
    currentSortDir: 'asc',
    currentSortType: 'DESIGN'};