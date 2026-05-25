<?php

namespace Database\Seeders;

use App\Models\Feedback;
use Illuminate\Database\Seeder;

class FeedbackStaticSeeder extends Seeder
{
    public function run(): void
    {
        $feedback = [
            [
                'employee_name' => 'Aarav Sharma',
                'employee_id' => 'PS-101',
                'feedback_place' => 'office',
                'feedback_date' => now()->subDays(4)->toDateString(),
                'department' => 'Electrical Maintenance',
                'shift_timing' => 'Morning',
                'safety_rating' => 5,
                'workstation_rating' => 4,
                'equipment_rating' => 5,
                'overall_satisfaction' => 5,
                'strengths' => 'Clear operating process and reliable safety checks during power supply monitoring.',
                'improvements' => 'Add one more tool kit near the control panel for faster minor maintenance work.',
                'additional_comments' => 'Good coordination between team members.',
                'recommend_position' => 'yes',
            ],
            [
                'employee_name' => 'Priya Verma',
                'employee_id' => 'PS-102',
                'feedback_place' => 'airport',
                'feedback_date' => now()->subDays(3)->toDateString(),
                'department' => 'Operations',
                'shift_timing' => 'Evening',
                'safety_rating' => 4,
                'workstation_rating' => 4,
                'equipment_rating' => 3,
                'overall_satisfaction' => 4,
                'strengths' => 'The role gives strong practical exposure to load handling and shift coordination.',
                'improvements' => 'Improve backup equipment availability during peak operating hours.',
                'additional_comments' => 'Overall a useful position for learning.',
                'recommend_position' => 'yes',
            ],
            [
                'employee_name' => 'Rohan Mehta',
                'employee_id' => 'PS-103',
                'feedback_place' => 'train_station',
                'feedback_date' => now()->subDays(2)->toDateString(),
                'department' => 'Power Control',
                'shift_timing' => 'Night',
                'safety_rating' => 3,
                'workstation_rating' => 3,
                'equipment_rating' => 4,
                'overall_satisfaction' => 3,
                'strengths' => 'Monitoring responsibilities are well defined and handover notes are helpful.',
                'improvements' => 'Night shift lighting and rest area facilities should be improved.',
                'additional_comments' => 'More supervision support would help new staff.',
                'recommend_position' => 'maybe',
            ],
            [
                'employee_name' => 'Neha Singh',
                'employee_id' => 'PS-104',
                'feedback_place' => 'school',
                'feedback_date' => now()->subDay()->toDateString(),
                'department' => 'Safety',
                'shift_timing' => 'Morning',
                'safety_rating' => 5,
                'workstation_rating' => 5,
                'equipment_rating' => 4,
                'overall_satisfaction' => 5,
                'strengths' => 'Safety procedures are followed seriously and PPE availability is good.',
                'improvements' => 'Provide monthly refresher training for emergency shutdown steps.',
                'additional_comments' => 'The position is well managed.',
                'recommend_position' => 'yes',
            ],
            [
                'employee_name' => 'Karan Patel',
                'employee_id' => 'PS-105',
                'feedback_place' => 'bus',
                'feedback_date' => now()->toDateString(),
                'department' => 'Maintenance',
                'shift_timing' => 'Evening',
                'safety_rating' => 2,
                'workstation_rating' => 3,
                'equipment_rating' => 2,
                'overall_satisfaction' => 2,
                'strengths' => 'Team members respond quickly when equipment issues are reported.',
                'improvements' => 'Old panels and testing devices should be replaced to reduce repeated faults.',
                'additional_comments' => 'Needs equipment upgrade priority.',
                'recommend_position' => 'no',
            ],
        ];

        foreach ($feedback as $item) {
            Feedback::updateOrCreate(
                ['employee_id' => $item['employee_id']],
                $item
            );
        }
    }
}
