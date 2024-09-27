import axios from "axios";
import { ReportedUser } from "resources/types/admin";
import { NewSuspension } from "resources/types/user";

export const suspendUser = async(suspension: NewSuspension): Promise<ReportedUser[]> => {
    const {data} = await axios.post(`/admin/suspend/${suspension.user_id}`, suspension);
    return data.data.reported_users;
};