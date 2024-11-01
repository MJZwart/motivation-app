import { Village } from "resources/types/village";
import { ref } from "vue";

export const activeVillage = ref<Village | null>();

// TODO Ensure that the reward is always updated when fetching pages that has the reward visible (dash/overview/profile) so this becomes obsolete