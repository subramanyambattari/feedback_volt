<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function create(): View
    {
        return view('feedback.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateFeedback($request);
        $validated['user_id'] = $request->user()->id;

        Feedback::create($validated);

        return redirect()
            ->route('feedback.create')
            ->with('success', 'Feedback submitted successfully.');
    }

    public function edit(Feedback $feedback): View
    {
        $this->authorizeFeedbackOwner($feedback);

        return view('feedback.edit', compact('feedback'));
    }

    public function update(Request $request, Feedback $feedback): RedirectResponse
    {
        $this->authorizeFeedbackOwner($feedback);

        $feedback->update($this->validateFeedback($request));

        return redirect()
            ->route('feedback.index')
            ->with('success', 'Feedback updated successfully.');
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $this->authorizeFeedbackOwner($feedback, allowAdmin: true);

        $feedback->delete();

        return redirect()
            ->route('feedback.index')
            ->with('success', 'Feedback deleted successfully.');
    }

    public function index(): View
    {
        $feedback = Feedback::query()
            ->when(! auth()->user()->isAdmin(), function ($query) {
                $query->where(function ($query) {
                    $query->where('user_id', auth()->id())
                        ->orWhereNull('user_id');
                });
            })
            ->latest()
            ->get();

        return view('feedback.index', compact('feedback'));
    }

    private function validateFeedback(Request $request): array
    {
        return $request->validate([
            'employee_name' => ['required', 'string', 'max:255'],
            'employee_id' => ['nullable', 'string', 'max:100'],
            'feedback_type' => ['required', 'in:complaint,suggestion'],
            'feedback_place' => ['required', 'in:home,office,bus,airport,train_station,school,hospital,market,public_place,other'],
            'feedback_date' => ['required', 'date'],
            'department' => ['required', 'string', 'max:255'],
            'shift_timing' => ['required', 'string', 'max:100'],
            'safety_rating' => ['required', 'integer', 'between:1,5'],
            'workstation_rating' => ['required', 'integer', 'between:1,5'],
            'equipment_rating' => ['required', 'integer', 'between:1,5'],
            'overall_satisfaction' => ['required', 'integer', 'between:1,5'],
            'strengths' => ['required', 'string', 'max:1000'],
            'improvements' => ['required', 'string', 'max:1000'],
            'additional_comments' => ['nullable', 'string', 'max:1000'],
            'recommend_position' => ['required', 'in:yes,no,maybe'],
        ]);
    }

    private function authorizeFeedbackOwner(Feedback $feedback, bool $allowAdmin = false): void
    {
        if ($allowAdmin && auth()->user()->isAdmin()) {
            return;
        }

        if ($feedback->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
