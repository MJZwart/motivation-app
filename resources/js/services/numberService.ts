const numberNames = ['thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion'];
const numberShorthands = ['k', 'M', 'B', 'T', 'Qa', 'Qi'];

export function parseBigNumbers(number: number | string, longNames = false): string {
    let shorthand = -1;
    let nrCopy = typeof number === 'string' ? parseInt(number) : number;
    while (shorthand < numberShorthands.length) {
        if (nrCopy / 1000 > 0.99) {
            nrCopy = nrCopy / 1000
            shorthand++;
            continue;
        }
        break;
    }
    return applyShorthand(Math.round(nrCopy), shorthand, longNames);
}

function applyShorthand(number: number, shorthandIdx: number, longNames = false) {
    if (shorthandIdx < 0) return `${number}`;
    if (longNames)
        return `${number} ${numberNames[shorthandIdx]}`;
    return `${number}${numberShorthands[shorthandIdx]}`;
}