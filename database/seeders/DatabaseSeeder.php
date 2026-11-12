<?php

namespace Database\Seeders;

use App\Models\ProgrammeSetting;
use App\Models\TrainingCentre;
use App\Models\Topic;
use App\Models\TopicProgress;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Seed the 20 pre-configured users with specific roles & permissions
        $this->call(UserSeeder::class);

        // 1. Initial Programme Settings
        ProgrammeSetting::firstOrCreate(
            ['id' => 1],
            [
                'start_date' => '2026-11-02',
                'weeks' => 20,
                'include_saturday' => false,
            ]
        );

        // 2. Initial Training Centres
        $centres = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        foreach ($centres as $index => $code) {
            TrainingCentre::firstOrCreate(
                ['code' => $code],
                [
                    'name' => 'Training Centre ' . $code,
                    'manager_name' => '',
                    'contact' => '',
                    'sort_order' => $index,
                ]
            );
        }

        // 3. Initial Topics
        $topics = [
            [1, 'Teacher Educator as a Professional', 42],
            [2, 'Sociological Background of Education', 45],
            [3, 'Guidance & Counselling', 54],
            [4, 'Technology for Education', 87],
            [5, 'Educational Law, Admin & Mgmt', 54],
            [6, 'Psychological Foundations of T-L', 63],
            [7, 'Curriculum, Measurement & Evaluation', 40],
            [8, 'Teacher Educator as a Researcher', 51],
            [9, 'T-L Methods & Techniques', 75],
        ];

        foreach ($topics as [$num, $title, $hours]) {
            Topic::firstOrCreate(
                ['topic_number' => $num],
                [
                    'title' => $title,
                    'required_hours' => $hours,
                ]
            );
        }

        // 4. Initial Progress records for each centre & topic
        $allCentres = TrainingCentre::all();
        $allTopics = Topic::all();

        foreach ($allCentres as $centre) {
            foreach ($allTopics as $topic) {
                TopicProgress::firstOrCreate(
                    [
                        'centre_id' => $centre->id,
                        'topic_id' => $topic->id,
                    ],
                    [
                        'manual_hours' => 0,
                        'remarks' => '',
                    ]
                );
            }
        }
    }
}
