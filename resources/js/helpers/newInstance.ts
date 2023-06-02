export function getNewSuspension(userId: number) {
    return {
        reason: '',
        days: 0,
        indefinite: false,
        close_reports: true,
        user_id: userId,
    }
}