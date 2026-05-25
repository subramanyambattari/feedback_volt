<?php

namespace Database\Seeders;

use App\Models\AdminNotification;
use App\Models\Feedback;
use Illuminate\Database\Seeder;

class DemoFeedbackResponseNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $feedbackRows = [
            ['DEMO-001', 'Ananya Rao', 'complaint', 'home', 'Power Distribution', 'Morning', 4, 4, 3, 4, 'yes', 'Voltage was restored quickly after reporting the issue.', 'Add clearer updates during outage repair work.', 'queued'],
            ['DEMO-002', 'Vikram Singh', 'suggestion', 'office', 'Operations', 'Evening', 5, 4, 4, 5, 'yes', 'The support team handled load balancing well.', 'Provide a weekly preventive maintenance checklist.', 'completed'],
            ['DEMO-003', 'Meera Nair', 'complaint', 'school', 'Safety', 'Morning', 2, 3, 2, 2, 'no', 'Staff responded politely to the complaint.', 'Replace old wiring near the classroom supply board.', 'in_progress'],
            ['DEMO-004', 'Arjun Das', 'suggestion', 'hospital', 'Emergency Backup', 'Night', 5, 5, 4, 5, 'yes', 'Backup supply started without delay during testing.', 'Keep spare fuses available near the generator room.', 'completed'],
            ['DEMO-005', 'Kavya Menon', 'complaint', 'market', 'Field Service', 'Evening', 3, 3, 3, 3, 'maybe', 'Technician visit was scheduled on the same day.', 'Improve communication about expected repair time.', 'assigned'],
            ['DEMO-006', 'Rahul Joshi', 'complaint', 'public_place', 'Maintenance', 'Night', 2, 2, 2, 2, 'no', 'Issue location was recorded accurately.', 'Street light supply faults need faster escalation.', 'in_progress'],
            ['DEMO-007', 'Sneha Kapoor', 'suggestion', 'airport', 'Power Control', 'Morning', 4, 5, 4, 4, 'yes', 'Monitoring dashboard helped identify the load spike.', 'Add SMS alerts for repeated voltage fluctuation.', 'completed'],
            ['DEMO-008', 'Nitin Kumar', 'complaint', 'train_station', 'Electrical Maintenance', 'Evening', 3, 4, 3, 3, 'maybe', 'Power returned before peak passenger movement.', 'Improve panel labeling for quicker fault tracing.', 'assigned'],
            ['DEMO-009', 'Isha Patel', 'suggestion', 'bus', 'Operations', 'Morning', 4, 4, 5, 4, 'yes', 'Equipment condition was better after recent servicing.', 'Schedule inspections before heavy travel hours.', 'completed'],
            ['DEMO-010', 'Rohan Verma', 'complaint', 'other', 'Customer Support', 'Night', 1, 2, 2, 1, 'no', 'Complaint form was easy to submit.', 'Urgent outage complaints need priority call-back.', 'queued'],
        ];

        foreach ($feedbackRows as $index => $row) {
            [
                $employeeId,
                $employeeName,
                $feedbackType,
                $place,
                $department,
                $shift,
                $safety,
                $workstation,
                $equipment,
                $satisfaction,
                $recommend,
                $strengths,
                $improvements,
                $progress,
            ] = $row;

            Feedback::updateOrCreate(
                ['employee_id' => $employeeId],
                [
                    'user_id' => null,
                    'feedback_type' => $feedbackType,
                    'assigned_technician_id' => null,
                    'repair_progress' => $progress,
                    'employee_name' => $employeeName,
                    'employee_id' => $employeeId,
                    'feedback_place' => $place,
                    'feedback_date' => now()->subDays(10 - $index)->toDateString(),
                    'department' => $department,
                    'shift_timing' => $shift,
                    'safety_rating' => $safety,
                    'workstation_rating' => $workstation,
                    'equipment_rating' => $equipment,
                    'overall_satisfaction' => $satisfaction,
                    'strengths' => $strengths,
                    'improvements' => $improvements,
                    'additional_comments' => 'Demo response data for dashboard and responses page.',
                    'recommend_position' => $recommend,
                ]
            );
        }

        $notifications = [
            ['New feedback received', 'DEMO-001 feedback has been added to the responses list.'],
            ['Low rating alert', 'DEMO-003 needs review because safety and equipment ratings are low.'],
            ['Complaint assigned', 'DEMO-005 is ready for technician assignment review.'],
            ['Emergency follow up', 'DEMO-006 reports repeated public place supply trouble.'],
            ['Positive response recorded', 'DEMO-007 shows strong power control satisfaction.'],
            ['Maintenance reminder', 'DEMO-008 needs panel labeling improvement follow up.'],
            ['Inspection suggested', 'DEMO-009 recommends pre-peak equipment inspections.'],
            ['Urgent complaint logged', 'DEMO-010 has very low satisfaction and needs priority attention.'],
            ['Dashboard data updated', 'Ten demo feedback responses are available for reports.'],
            ['Notification test complete', 'Sample notification records are ready for the notifications page.'],
        ];

        foreach ($notifications as $index => [$title, $message]) {
            AdminNotification::updateOrCreate(
                ['title' => $title],
                [
                    'user_id' => null,
                    'recipient_user_id' => null,
                    'category' => $index % 3 === 0 ? 'tracking_completed' : 'admin',
                    'title' => $title,
                    'message' => $message,
                ]
            );
        }
    }
}
