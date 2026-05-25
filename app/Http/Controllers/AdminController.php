<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        $reports = [
            'users' => User::count(),
            'technicians' => User::where('role', 'technician')->count(),
            'feedback' => Feedback::count(),
            'low_safety' => Feedback::where('safety_rating', '<=', 2)->count(),
            'notices' => AdminNotification::count(),
        ];

        $latestNotices = AdminNotification::with('user')->latest()->take(5)->get();

        return view('admin.index', compact('reports', 'latestNotices'));
    }

    public function sendNotification(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        AdminNotification::create([
            'user_id' => auth()->id(),
            'category' => 'admin',
            'title' => $validated['title'],
            'message' => $validated['message'],
        ]);

        return redirect()
            ->route('admin.index')
            ->with('admin_success', 'Notification sent to all users.');
    }

    public function technicians(): View
    {
        $this->authorizeAdmin();

        $technicians = User::query()
            ->where('role', 'technician')
            ->orderBy('name')
            ->get()
            ->map(function (User $technician): User {
                $technician->assigned_tasks_count = Feedback::query()
                    ->where('assigned_technician_id', $technician->id)
                    ->whereNotIn('repair_progress', ['completed'])
                    ->count();

                return $technician;
            });

        $complaints = Feedback::query()
            ->with('assignedTechnician')
            ->where('feedback_type', 'complaint')
            ->latest()
            ->take(12)
            ->get();

        $progressOptions = [
            'queued' => 'Queued',
            'assigned' => 'Assigned',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
        ];

        return view('admin.technicians', compact('technicians', 'complaints', 'progressOptions'));
    }

    public function assignTechnician(Request $request, Feedback $feedback): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'assigned_technician_id' => ['nullable', 'string'],
            'repair_progress' => ['required', 'in:queued,assigned,in_progress,completed'],
        ]);

        $technicianId = $validated['assigned_technician_id'] ?: null;

        if ($technicianId) {
            $technician = User::where('role', 'technician')->findOrFail($technicianId);
            $technicianId = $technician->id;
        }

        $feedback->update([
            'assigned_technician_id' => $technicianId,
            'repair_progress' => $validated['repair_progress'],
        ]);

        return redirect()
            ->route('admin.technicians')
            ->with('admin_success', 'Technician assignment updated.');
    }

    public function storeTechnician(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'technician',
        ]);

        return redirect()
            ->route('admin.technicians')
            ->with('admin_success', 'Technician account created.');
    }

    private function authorizeAdmin(): void
    {
        if (! auth()->user()?->isAdmin()) {
            abort(403);
        }
    }
}
