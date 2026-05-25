<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function profile(): View
    {
        $user = auth()->user();

        $userFeedback = Feedback::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $feedbackStats = (object) [
            'total' => $userFeedback->count(),
            'satisfaction' => $this->averageRating($userFeedback, 'overall_satisfaction'),
            'safety' => $this->averageRating($userFeedback, 'safety_rating'),
        ];

        $latestFeedback = $userFeedback->take(3);

        return view('account.profile', compact('user', 'feedbackStats', 'latestFeedback'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($validated);

        return redirect()
            ->route('profile')
            ->with('profile_success', 'Profile details updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('profile')
            ->with('password_success', 'Password updated successfully.');
    }

    public function notifications(): View
    {
        $readAt = session('notifications_read_at');

        $userFeedback = Feedback::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $newComplaintAlerts = $userFeedback
            ->where('feedback_type', 'complaint')
            ->where('overall_satisfaction', '<=', 3);
        $newComplaints = $newComplaintAlerts->take(5);

        $lowRatings = $userFeedback
            ->where('overall_satisfaction', '<=', 2)
            ->merge($userFeedback->where('safety_rating', '<=', 2))
            ->unique('id')
            ->take(5);

        $recentFeedback = $userFeedback->take(5);
        $adminNotifications = auth()->user()->isAdmin()
            ? AdminNotification::where('category', 'admin')->latest()->take(8)->get()
            : collect();
        $trackingNotifications = AdminNotification::query()
            ->where('category', 'tracking_completed')
            ->where('recipient_user_id', auth()->id())
            ->latest()
            ->take(8)
            ->get();

        $systemUpdates = collect([
            [
                'title' => 'System maintenance update',
                'message' => 'Feedback records and reports are scheduled for routine local database maintenance.',
                'time' => now()->timezone('Asia/Kolkata')->addDay()->format('d M Y, h:i A') . ' IST',
            ],
            [
                'title' => 'Emergency outage notice',
                'message' => 'If power supply position feedback mentions safety or outage risk, review it immediately.',
                'time' => now()->timezone('Asia/Kolkata')->format('d M Y, h:i A') . ' IST',
            ],
            [
                'title' => 'New complaint alerts enabled',
                'message' => 'Low satisfaction and safety ratings now appear as notification alerts.',
                'time' => now()->timezone('Asia/Kolkata')->subHour()->format('d M Y, h:i A') . ' IST',
            ],
        ]);

        $unreadComplaintAlerts = $readAt
            ? $newComplaintAlerts->where('created_at', '>', $readAt)->count()
            : $newComplaintAlerts->count();
        $unreadAdminNotices = auth()->user()->isAdmin()
            ? ($readAt
                ? AdminNotification::where('category', 'admin')->where('created_at', '>', $readAt)->count()
                : AdminNotification::where('category', 'admin')->count())
            : 0;
        $unreadTrackingNotices = $readAt
            ? AdminNotification::where('category', 'tracking_completed')->where('recipient_user_id', auth()->id())->where('created_at', '>', $readAt)->count()
            : AdminNotification::where('category', 'tracking_completed')->where('recipient_user_id', auth()->id())->count();
        $unreadSystemUpdates = $readAt ? 0 : $systemUpdates->count();
        $unreadCount = $unreadComplaintAlerts + $unreadSystemUpdates + $unreadAdminNotices + $unreadTrackingNotices;

        session(['notifications_read_at' => now()]);

        return view('account.notifications', compact('lowRatings', 'recentFeedback', 'newComplaints', 'systemUpdates', 'adminNotifications', 'trackingNotifications', 'unreadCount'));
    }

    public function emergency(): View
    {
        if (auth()->user()->isAdmin()) {
            abort(403);
        }

        $userFeedback = Feedback::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $highRiskFeedback = $userFeedback
            ->where('safety_rating', '<=', 2)
            ->merge($userFeedback->where('overall_satisfaction', '<=', 2))
            ->unique('id')
            ->take(5);

        $emergencyAlerts = collect([
            [
                'type' => 'High-risk alert',
                'level' => 'Critical',
                'message' => 'Safety rating below acceptable level. Immediate inspection is recommended.',
                'time' => now()->timezone('Asia/Kolkata')->format('d M Y, h:i A') . ' IST',
                'color' => 'rose',
            ],
            [
                'type' => 'Overload warning',
                'level' => 'High',
                'message' => 'Load demand may exceed safe working range during peak usage hours.',
                'time' => now()->timezone('Asia/Kolkata')->subMinutes(25)->format('d M Y, h:i A') . ' IST',
                'color' => 'amber',
            ],
            [
                'type' => 'Power cut report',
                'level' => 'Medium',
                'message' => 'Power interruption reported in a feedback zone. Track repeated outage patterns.',
                'time' => now()->timezone('Asia/Kolkata')->subHour()->format('d M Y, h:i A') . ' IST',
                'color' => 'cyan',
            ],
            [
                'type' => 'Emergency shutdown notification',
                'level' => 'Critical',
                'message' => 'Emergency shutdown protocol should be reviewed if equipment rating is repeatedly low.',
                'time' => now()->timezone('Asia/Kolkata')->subHours(2)->format('d M Y, h:i A') . ' IST',
                'color' => 'rose',
            ],
        ]);

        return view('account.emergency', compact('highRiskFeedback', 'emergencyAlerts'));
    }

    public function complaintTracking(): View
    {
        $feedback = Feedback::query()
            ->with('assignedTechnician')
            ->where('feedback_type', 'complaint')
            ->when(auth()->user()->isTechnician(), function ($query) {
                $query->where('assigned_technician_id', auth()->id());
            })
            ->when(! auth()->user()->isAdmin() && ! auth()->user()->isTechnician(), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get()
            ->map(function (Feedback $item) {
                $steps = ['Submitted', 'Reviewing', 'Processing', 'Resolved'];
                $progressStep = [
                    'queued' => 0,
                    'assigned' => 1,
                    'in_progress' => 2,
                    'completed' => 3,
                ];
                $currentStep = $progressStep[$item->repair_progress] ?? 0;

                $technicians = [
                    'home' => 'Ravi Kumar',
                    'office' => 'Anita Sharma',
                    'school' => 'Neha Singh',
                    'hospital' => 'Arjun Patel',
                    'market' => 'Meera Das',
                    'public_place' => 'Karan Verma',
                    'other' => 'Field Support Team',
                ];

                $item->tracking_id = 'CMP-' . $item->created_at->format('Y') . '-' . str_pad((string) $item->id, 5, '0', STR_PAD_LEFT);
                $item->tracking_steps = $steps;
                $item->tracking_current_step = $currentStep;
                $item->tracking_status = $steps[$currentStep];
                $item->assigned_technician = $item->assignedTechnician?->name
                    ?? ($technicians[$item->feedback_place] ?? 'Regional Technician');
                $item->resolution_date = $currentStep === 3
                    ? $item->updated_at->copy()->addDay()->timezone('Asia/Kolkata')->format('d M Y')
                    : 'Pending';

                return $item;
            });

        return view('account.complaint-tracking', compact('feedback'));
    }

    public function completeAssignedComplaint(Feedback $feedback): RedirectResponse
    {
        if (! auth()->user()->isTechnician()) {
            abort(403);
        }

        if ($feedback->feedback_type !== 'complaint' || $feedback->assigned_technician_id !== auth()->id()) {
            abort(403);
        }

        if ($feedback->repair_progress === 'completed') {
            return redirect()
                ->route('complaints.tracking')
                ->with('success', 'Complaint is already marked as completed.');
        }

        $feedback->update([
            'repair_progress' => 'completed',
        ]);

        if ($feedback->user_id) {
            AdminNotification::create([
                'user_id' => auth()->id(),
                'recipient_user_id' => $feedback->user_id,
                'category' => 'tracking_completed',
                'title' => 'Complaint tracking completed',
                'message' => auth()->user()->name . ' completed your complaint CMP-' . $feedback->created_at->format('Y') . '-' . str_pad((string) $feedback->id, 5, '0', STR_PAD_LEFT) . '. Your tracking progress is now resolved.',
            ]);
        }

        return redirect()
            ->route('complaints.tracking')
            ->with('success', 'Task marked as completed and admin has been notified.');
    }

    public function assistant(): View
    {
        $suggestedQuestions = [
            'Why power fluctuation occurs?',
            'How to report transformer failure?',
            'Safety suggestions?',
        ];

        return view('account.assistant', compact('suggestedQuestions'));
    }

    public function askAssistant(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $question = trim($validated['message']);
        $reply = $this->localAssistantReply($question);

        if (config('services.openai.key')) {
            try {
                $response = Http::withToken(config('services.openai.key'))
                    ->timeout(12)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => config('services.openai.model'),
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'You are a concise power supply complaint assistant. Give safe, practical guidance and ask users to contact emergency services for immediate danger.',
                            ],
                            ['role' => 'user', 'content' => $question],
                        ],
                        'temperature' => 0.3,
                        'max_tokens' => 220,
                    ]);

                if ($response->successful()) {
                    $reply = data_get($response->json(), 'choices.0.message.content', $reply);
                }
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return response()->json([
            'reply' => $reply,
        ]);
    }

    private function localAssistantReply(string $question): string
    {
        $normalized = Str::lower($question);

        if (Str::contains($normalized, ['fluctuation', 'voltage', 'power up', 'power down'])) {
            return 'Power fluctuation usually happens because of overload, loose wiring, faulty appliances, transformer load changes, or sudden grid demand. Switch off sensitive devices, note the time and location, and submit a complaint if it repeats.';
        }

        if (Str::contains($normalized, ['transformer', 'failure', 'blast', 'sparking'])) {
            return 'To report transformer failure, open the feedback form, choose the affected place, describe visible signs like sparks, burning smell, outage area, and time, then submit it. Keep distance from the transformer and call emergency support if there is smoke, fire, or exposed wiring.';
        }

        if (Str::contains($normalized, ['safety', 'suggestion', 'shock', 'wire'])) {
            return 'Safety suggestions: do not touch wet switches, avoid overloaded sockets, keep children away from panels, unplug devices during severe fluctuation, and report exposed wires or burning smells immediately.';
        }

        return 'I can help with power fluctuation, transformer failure reporting, outage notices, and electrical safety. Share the issue location, symptoms, and urgency so I can guide the next step.';
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
