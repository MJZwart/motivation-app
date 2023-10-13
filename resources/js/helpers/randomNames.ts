import animals from './json/animals.json';
import adjectives from './json/adjectives.json';
import villageNames from './json/villagenames.json';
import characterClasses from './json/characterclasses.json';

export function getRandomUsername() {
    return getRandomAdjective() + getRandomAnimal() + Math.round(Math.random() * 10000);
}

export function getRandomVillageName() {
    let villageName = '';
    const prefix = Math.random() > 0.8 ?
        villageNames.prefixes[Math.round(Math.random() * (villageNames.prefixes.length - 1))] :
        null;
    if (prefix) villageName += prefix + ' ';
    villageName += villageNames.nouns[Math.round(Math.random() * (villageNames.nouns.length - 1))] +
        villageNames.suffixes[Math.round(Math.random() * (villageNames.suffixes.length - 1))];
    return villageName;
}

export function getRandomCharacterName() {
    return getRandomAdjective() + characterClasses[Math.round(Math.random() * (characterClasses.length - 1))];
}

function getRandomAdjective() {
    return adjectives[Math.round(Math.random() * (adjectives.length - 1))];
}

function getRandomAnimal() {
    return animals[Math.round(Math.random() * (animals.length - 1))];
}