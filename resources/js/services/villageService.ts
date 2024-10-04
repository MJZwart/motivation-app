import { Reward } from "resources/types/reward";
import { ref } from "vue";

export const activeReward = ref<Reward | null>(); // TODO Make sure naming is correct when character is phased out

// TODO Ensure that the reward is always updated when fetching pages that has the reward visible (dash/overview/profile) so this becomes obsolete