<template>
    <div class="row">
        <div class="col">
            <!-- 
                Button to update the changed experience points in the table
                -->
            <button class="mt-4" @click="updateExpPoints">{{ $t('update-exp-points') }}</button>
            <general-form-error />
            <hr />
            <!-- 
                Fields to add a new level
                -->
            <div class="m-1">
                <h5>{{$t('add-new-level')}}</h5>
                <div class="form-group">
                    <label for="level">{{$t('level')}}</label>
                    <input  
                        id="level" 
                        v-model="newLevel.level"
                        type="number" 
                        name="level" 
                        :placeholder="$t('level')"  />
                    <base-form-error name="level" /> 
                </div>
                <div class="form-group">
                    <label for="points">{{$t('points')}}</label>
                    <input  
                        id="points" 
                        v-model="newLevel.experience_points"
                        type="number" 
                        name="points" 
                        :placeholder="$t('points')"  />
                    <base-form-error name="experience_points" /> 
                </div>
                <button @click="addNewLevel">{{ $t('add-level') }}</button>
            </div>
        </div>
        <!-- 
            The Experience points table
            -->
        <div class="col">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(level, index) in experiencePoints" :key="index">
                        <td>{{level.level}}</td>
                        <td>
                            <input v-model="experiencePoints[index].experience_points" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import GeneralFormError from '/js/components/global/GeneralFormError.vue';
import {shallowRef, onMounted, computed, ref} from 'vue';
import {useAdminStore} from '/js/store/adminStore';
import {useMainStore} from '/js/store/store';
const adminStore = useAdminStore();
const mainStore = useMainStore();

onMounted(() => {
    experiencePoints.value = shallowRef(experience_points.value).value;
});

const experiencePoints = ref(null);
const newLevel = ref({});

const experience_points = computed(() => adminStore.experiencePoints);


function updateExpPoints() {
    clearErrors();
    adminStore.updateExpPoints(experiencePoints.value);
}
async function addNewLevel() {
    clearErrors();
    await adminStore.addNewLevel(newLevel.value);
    experiencePoints.value = shallowRef(experience_points).value;
}
function clearErrors() {
    mainStore.clearErrors();
}
</script>