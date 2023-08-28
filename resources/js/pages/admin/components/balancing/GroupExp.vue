<template>
    <div class="row">
        <div class="col">
            <h5>{{$t('manage-group-exp')}}</h5>
            <form @submit.prevent="submit">
                <SimpleInput
                    id="initialPoints"
                    v-model="groupExp.initialPoints"
                    type="number"
                    name="initialPoints"
                    :label="$t('initial-points')"
                    :placeholder="$t('initial-points')"
                />
                <SimpleInput
                    id="firstIncrement"
                    v-model="groupExp.firstIncrement"
                    type="number"
                    name="firstIncrement"
                    :label="$t('increments')"
                    :placeholder="$t('first-increment')"
                />
                <SimpleInput
                    id="firstIncrementMaxLevel"
                    v-model="groupExp.firstIncrementMaxLevel"
                    type="number"
                    name="firstIncrementMaxLevel"
                    max="500"
                    :label="$t('until-level')"
                    :placeholder="$t('first-increment-max-level')"
                />
                {{ $t('midway-point')}}: {{midwayPoint}}
                <SimpleInput
                    id="secondIncrement"
                    v-model="groupExp.secondIncrement"
                    type="number"
                    name="secondIncrement"
                    :label="$t('increments-after')"
                    :placeholder="$t('second-increment')"
                />
                <SimpleInput
                    id="maxLevel"
                    v-model="groupExp.maxLevel"
                    type="number"
                    name="maxLevel"
                    max="500"
                    :label="$t('max-level')"
                    :placeholder="$t('max-level')"
                />
                Experience cap: {{ cappedExp }}
                <span class="silent">All subsequent levels will have this as max experience points for their level</span>
                <button type="button" class="mr-2" @click="generateTable">Preview new experience table</button>
                <SubmitButton />
            </form>
        </div>
        <Loading v-if="loading" />
        <div v-else class="col d-flex">
            <div class="mr-2">
                <h4>Current experience table</h4>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>{{ $t('level') }}</th>
                            <th>{{ $t('points') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(level, index) in newGroupExp" :key="index">
                            <td>{{level.level}}</td>
                            <td>
                                {{ level.experience_points }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="newExpTable.length">
                <h4>New experience table</h4>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>{{ $t('level') }}</th>
                            <th>{{ $t('points') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(level, index) in newExpTable" :key="index">
                            <td>{{level.level}}</td>
                            <td>
                                {{ level.experience_points }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, onMounted, ref, watch, watchEffect} from 'vue';
import Loading from '/js/components/global/Loading.vue';
import {useAdminStore} from '/js/store/adminStore';
import {deepCopy} from '/js/helpers/copy';
import {ExperiencePoint} from 'resources/types/admin';
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
const adminStore = useAdminStore();

onMounted(async () => {
    currentGroupExp.value = await adminStore.getGroupExp();
    newGroupExp.value = deepCopy(currentGroupExp.value);
    getCurrentGroupExpCalculation();
    loading.value = false;
});

const currentGroupExp = ref<ExperiencePoint[]>([]);
const newGroupExp = ref<ExperiencePoint[]>([]);
const newExpTable = ref<ExperiencePoint[]>([]);

const groupExp = ref({
    initialPoints: 0,
    firstIncrement: 0,
    firstIncrementMaxLevel: 0,
    secondIncrement: 0,
    maxLevel: 0,
});
const loading = ref(true);

function getCurrentGroupExpCalculation() {
    const firstIncrement = currentGroupExp.value[1].experience_points - currentGroupExp.value[0].experience_points;
    const firstIncrementMaxLevel = findIncrementMaxLevel(firstIncrement);
    const secondIncrement = currentGroupExp.value[firstIncrementMaxLevel + 1].experience_points - 
        currentGroupExp.value[firstIncrementMaxLevel].experience_points;
    const maxLevel = currentGroupExp.value.length;

    groupExp.value = {
        initialPoints: currentGroupExp.value[0].experience_points,
        firstIncrement: firstIncrement,
        firstIncrementMaxLevel: firstIncrementMaxLevel,
        secondIncrement: secondIncrement,
        maxLevel: maxLevel,
    }
}
function findIncrementMaxLevel(increment: number) {
    for (let i = 0 ; i < currentGroupExp.value.length ; i++) {
        if (currentGroupExp.value[i].experience_points + increment !== currentGroupExp.value[i+1].experience_points) 
            return i + 1;
    }
    return 0;
}
const cappedExp = computed(() => {
    const levelsInFirstHalf = groupExp.value.firstIncrementMaxLevel - 1;
    const expInFirstHalf = levelsInFirstHalf * groupExp.value.firstIncrement;
    const levelsInSecondHalf = groupExp.value.maxLevel - (levelsInFirstHalf - 1);
    const expInSecondHalf = levelsInSecondHalf * groupExp.value.secondIncrement;
    return expInFirstHalf + expInSecondHalf + groupExp.value.initialPoints;
});

const midwayPoint = computed(() => {
    const levelsInFirstHalf = groupExp.value.firstIncrementMaxLevel - 1;
    const expInFirstHalf = levelsInFirstHalf * groupExp.value.firstIncrement;
    return expInFirstHalf + groupExp.value.initialPoints;
});

function generateTable() {
    loading.value = true;
    const table = [];
    for (let i = 0; i < groupExp.value.maxLevel ; i++) {
        let exp = groupExp.value.initialPoints;
        if (i < groupExp.value.firstIncrementMaxLevel)
            exp += i * groupExp.value.firstIncrement;
        else {
            const cumulative = (groupExp.value.firstIncrementMaxLevel) * groupExp.value.firstIncrement
            const secondHalf = i - groupExp.value.firstIncrementMaxLevel - 1;
            exp += secondHalf * groupExp.value.secondIncrement + cumulative;
        }
        table.push({level: i + 1, experience_points: exp})
    }
    newExpTable.value = table;
    loading.value = false;
}

async function submit() {
    loading.value = true;
    generateTable();
    newGroupExp.value = await adminStore.saveGroupExp(newExpTable.value);
    loading.value = false;
}

watchEffect(
    () => {
        if (typeof groupExp.value.initialPoints === 'string')
            groupExp.value.initialPoints = parseInt(groupExp.value.initialPoints);
    });
</script>