<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use stdClass;

class DashboardController extends Controller
{
    public function index(): View
    {
        $allFeedback = Feedback::latest()->get();
        $totalFeedback = $allFeedback->count();
        $latestFeedback = $allFeedback->take(5);

        $averages = (object) [
            'safety' => $this->averageRating($allFeedback, 'safety_rating'),
            'workstation' => $this->averageRating($allFeedback, 'workstation_rating'),
            'equipment' => $this->averageRating($allFeedback, 'equipment_rating'),
            'satisfaction' => $this->averageRating($allFeedback, 'overall_satisfaction'),
        ];

        $recommendations = $allFeedback->countBy('recommend_position');

        $departmentLeaders = $allFeedback
            ->groupBy('department')
            ->map(function (Collection $items, string $department): stdClass {
                return (object) [
                    'department' => $department,
                    'total' => $items->count(),
                    'average_rating' => $this->averageRating($items, 'overall_satisfaction'),
                ];
            })
            ->sortByDesc('total')
            ->take(4)
            ->values();

        $needsAttention = $allFeedback
            ->filter(fn (Feedback $feedback): bool => (int) $feedback->overall_satisfaction <= 2 || (int) $feedback->safety_rating <= 2)
            ->take(4)
            ->values();

        return view('dashboard', [
            'totalFeedback' => $totalFeedback,
            'latestFeedback' => $latestFeedback,
            'allFeedback' => $allFeedback,
            'averages' => $averages,
            'recommendations' => $recommendations,
            'departmentLeaders' => $departmentLeaders,
            'needsAttention' => $needsAttention,
        ]);
    }

    private function averageRating(Collection $feedback, string $field): float
    {
        $ratings = $feedback
            ->pluck($field)
            ->filter(fn ($rating): bool => is_numeric($rating));

        if ($ratings->isEmpty()) {
            return 0.0;
        }

        return (float) $ratings->avg();
    }
}
