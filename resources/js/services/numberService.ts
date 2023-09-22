const numberNames = ['thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion'];
const numberShorthands = ['k', 'M', 'B', 'T', 'Qa', 'Qi'];

let shorthand = -1;
    
export function parseBigNumbers(number: number | string | undefined, longNames = false, decimals = 2): string {
    if (number === undefined) return '';
    let nrCopy = typeof number === 'string' ? parseInt(number) : number;
    if (nrCopy < 1000) return nrCopy.toFixed();
    nrCopy = reduceToThreeDigits(nrCopy);
    const finalNumber = applyShorthand(reduceToDecimals(nrCopy, decimals), shorthand, longNames);
    shorthand = -1;
    return finalNumber;
}

function reduceToThreeDigits(number: number) {
    let nrCopy = number;
    while (shorthand < numberShorthands.length) {
        if (nrCopy / 1000 > 0.99) {
            nrCopy = nrCopy / 1000
            shorthand++;
            continue;
        }
        return nrCopy;
    }
    return nrCopy;
}

function reduceToDecimals(number: number, decimals: number) {
    return (Math.floor(number * 100) / 100).toFixed(decimals);
}

function applyShorthand(number: string, shorthandIdx: number, longNames = false) {
    if (shorthandIdx < 0) return `${number}`;
    if (longNames)
        return `${number} ${numberNames[shorthandIdx]}`;
    return `${number}${numberShorthands[shorthandIdx]}`;
}