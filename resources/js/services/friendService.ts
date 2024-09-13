import { computed, ref } from "vue";
import {Friend} from 'resources/types/friend';
import { user } from "./userService";

export const friends = computed<Friend[]>(() => user.value?.friends ?? []);