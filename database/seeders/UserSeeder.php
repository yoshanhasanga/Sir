<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed exactly 20 users across the 4 specific operational tiers.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('Pass@2026#Sec');

        // Delete test user if present
        User::where('email', 'test@example.com')->delete();

        // 1. Center Managers (6 users) - View Only
        $centerManagers = [
            ['name' => 'K. L. Bandara', 'email' => 'cm.colombo@sltes.gov.lk', 'designation' => 'Center Manager - Colombo (Centre A)', 'phone' => '+94 11 278 5410'],
            ['name' => 'M. R. Senanayake', 'email' => 'cm.kandy@sltes.gov.lk', 'designation' => 'Center Manager - Kandy (Centre B)', 'phone' => '+94 81 223 4567'],
            ['name' => 'W. T. Fernando', 'email' => 'cm.galle@sltes.gov.lk', 'designation' => 'Center Manager - Galle (Centre C)', 'phone' => '+94 91 224 5678'],
            ['name' => 'S. Tharmalingam', 'email' => 'cm.jaffna@sltes.gov.lk', 'designation' => 'Center Manager - Jaffna (Centre D)', 'phone' => '+94 21 222 3456'],
            ['name' => 'P. B. Jayasinghe', 'email' => 'cm.kurunegala@sltes.gov.lk', 'designation' => 'Center Manager - Kurunegala (Centre E)', 'phone' => '+94 37 222 4567'],
            ['name' => 'A. M. Dissanayake', 'email' => 'cm.badulla@sltes.gov.lk', 'designation' => 'Center Manager - Badulla (Centre F)', 'phone' => '+94 55 222 6789'],
        ];

        foreach ($centerManagers as $cm) {
            User::updateOrCreate(
                ['email' => $cm['email']],
                [
                    'name' => $cm['name'],
                    'password' => $defaultPassword,
                    'role' => 'center_manager',
                    'designation' => $cm['designation'],
                    'phone' => $cm['phone'],
                    'can_view' => true,
                    'can_edit' => false,
                    'can_delete' => false,
                    'can_approve' => false,
                    'is_active' => true,
                ]
            );
        }

        // 2. Topic Leaders (9 users) - View Only
        $topicLeaders = [
            ['name' => 'Prof. Gamini Gunawardena', 'email' => 'tl.t1@sltes.gov.lk', 'designation' => 'Topic Leader - Teacher Educator as a Professional (T1)'],
            ['name' => 'Dr. Chandra Weerakoon', 'email' => 'tl.t2@sltes.gov.lk', 'designation' => 'Topic Leader - Sociological Background of Education (T2)'],
            ['name' => 'Mrs. Sujatha Alahakoon', 'email' => 'tl.t3@sltes.gov.lk', 'designation' => 'Topic Leader - Guidance & Counselling (T3)'],
            ['name' => 'Eng. Nihal Ranatunga', 'email' => 'tl.t4@sltes.gov.lk', 'designation' => 'Topic Leader - Technology for Education (T4)'],
            ['name' => 'Attorney K. V. Karunaratne', 'email' => 'tl.t5@sltes.gov.lk', 'designation' => 'Topic Leader - Educational Law, Admin & Mgmt (T5)'],
            ['name' => 'Dr. Anula Rathnayake', 'email' => 'tl.t6@sltes.gov.lk', 'designation' => 'Topic Leader - Psychological Foundations of T-L (T6)'],
            ['name' => 'Mr. Priyantha Abeywickrama', 'email' => 'tl.t7@sltes.gov.lk', 'designation' => 'Topic Leader - Curriculum, Measurement & Evaluation (T7)'],
            ['name' => 'Prof. S. Somaratne', 'email' => 'tl.t8@sltes.gov.lk', 'designation' => 'Topic Leader - Teacher Educator as a Researcher (T8)'],
            ['name' => 'Mrs. Mallika Jayasuriya', 'email' => 'tl.t9@sltes.gov.lk', 'designation' => 'Topic Leader - T-L Methods & Techniques (T9)'],
        ];

        foreach ($topicLeaders as $tl) {
            User::updateOrCreate(
                ['email' => $tl['email']],
                [
                    'name' => $tl['name'],
                    'password' => $defaultPassword,
                    'role' => 'topic_leader',
                    'designation' => $tl['designation'],
                    'can_view' => true,
                    'can_edit' => false,
                    'can_delete' => false,
                    'can_approve' => false,
                    'is_active' => true,
                ]
            );
        }

        // 3. Ministry of Education - Designated Approvers (2 users)
        // High-security role: Required to authorize/approve all data deletions and sensitive modifications
        $moeApprovers = [
            ['name' => 'Hon. W. M. H. Wijesinghe', 'email' => 'moe.approver1@moe.gov.lk', 'designation' => 'MoE Additional Secretary / Chief Authorizer', 'phone' => '+94 11 278 5141'],
            ['name' => 'Dr. R. H. Gunathilake', 'email' => 'moe.approver2@moe.gov.lk', 'designation' => 'MoE Director General (Teacher Education) / Authorizer', 'phone' => '+94 11 278 5142'],
        ];

        foreach ($moeApprovers as $ma) {
            User::updateOrCreate(
                ['email' => $ma['email']],
                [
                    'name' => $ma['name'],
                    'password' => $defaultPassword,
                    'role' => 'moe_approver',
                    'designation' => $ma['designation'],
                    'phone' => $ma['phone'],
                    'can_view' => true,
                    'can_edit' => true,
                    'can_delete' => true,
                    'can_approve' => true,
                    'is_active' => true,
                ]
            );
        }

        // 4. Ministry of Education - Admins / Editors (3 users)
        // Full View, Edit, and Request Deletion capabilities
        $moeAdmins = [
            ['name' => 'H. M. Sunil Shantha', 'email' => 'moe.director@moe.gov.lk', 'designation' => 'MoE SLTES Programme Director', 'phone' => '+94 11 278 5150'],
            ['name' => 'K. P. Chaminda Silva', 'email' => 'moe.planner1@moe.gov.lk', 'designation' => 'MoE Senior Timetable & Logistics Planner', 'phone' => '+94 11 278 5151'],
            ['name' => 'N. D. Kasun Wickramasinghe', 'email' => 'moe.planner2@moe.gov.lk', 'designation' => 'MoE Teacher Education Operations Officer', 'phone' => '+94 11 278 5152'],
        ];

        foreach ($moeAdmins as $adm) {
            User::updateOrCreate(
                ['email' => $adm['email']],
                [
                    'name' => $adm['name'],
                    'password' => $defaultPassword,
                    'role' => 'moe_admin',
                    'designation' => $adm['designation'],
                    'phone' => $adm['phone'],
                    'can_view' => true,
                    'can_edit' => true,
                    'can_delete' => true,
                    'can_approve' => false,
                    'is_active' => true,
                ]
            );
        }
    }
}


