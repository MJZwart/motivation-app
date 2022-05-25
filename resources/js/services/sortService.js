/**
 * @param {any[]} items
 * @param {String} key
 * @param {string | undefined} dir
 */
export function sortValues(items, key, dir = 'asc') {
    return items.slice().sort(compareValues(key, dir));
}
/**
 * @param {String} key
 * @param {string | undefined} order
 */
function compareValues(key, order = 'asc') {
    // eslint-disable-next-line complexity
    return function innerSort(/** @type {{ [x: string]: any; }} */ a, /** @type {{ [x: string]: any; }} */ b) {
        if (!Object.prototype.hasOwnProperty.call(a, key) || !Object.prototype.hasOwnProperty.call(b, key))
            return 0;
        const varA = (typeof a[key] === 'string') ? a[key].toUpperCase() : a[key];
        const varB = (typeof b[key] === 'string') ? b[key].toUpperCase() : b[key];

        let comparison = 0;
        if (varA > varB) comparison = 1;
        else if (varA < varB) comparison = -1;

        return ((order === 'desc') ? (comparison * -1) : comparison);
    };
}
