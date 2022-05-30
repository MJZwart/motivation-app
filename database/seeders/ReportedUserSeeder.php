<?php

namespace Database\Seeders;

use App\Models\ReportedUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('admin', false)->pluck('id')->toArray();
        $reportingUsers = User::where('admin', false)->pluck('id')->toArray();
        shuffle($users);
        shuffle($reportingUsers);
        $reasons = ['SPAM', 'HARASSMENT', 'INAPPROPRIATE_NAME', 'OTHER'];

        for($i = 0 ; $i < 20 ; $i++) {
            $randReason = rand(0, count($reasons) -1);
            ReportedUser::create([
                'reported_user_id' => $users[$i],
                'reported_by_user_id' => $reportingUsers[$i],
                'comment' => 'Seeder report',
                'reason' => $reasons[$randReason],
            ]);
        }
    }
}
