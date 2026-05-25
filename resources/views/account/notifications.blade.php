@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl">
        <div class="hero-card mb-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Notifications</p>
                    <h1 class="mt-3 text-4xl font-bold">Notification Center</h1>
                    <p class="mt-4 max-w-3xl text-sm text-slate-200">
                        New complaint alerts, system updates, and emergency outage notices in one place.
                    </p>
                </div>
                <span class="w-fit rounded-full bg-rose-300 px-4 py-2 text-sm font-extrabold text-slate-950">
                    {{ $unreadCount }} Unread
                </span>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            @if (auth()->user()->isAdmin())
                <div class="panel-card p-6 lg:col-span-3">
                    <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Admin Messages</p>
                    <h2 class="mt-2 text-2xl font-bold">Notifications From Admin</h2>

                    <div class="mt-6 grid gap-3 md:grid-cols-2">
                        @forelse ($adminNotifications as $notice)
                            <div class="rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4">
                                <p class="font-semibold">{{ $notice->title }}</p>
                                <p class="mt-1 text-xs text-amber-300">
                                    {{ $notice->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} IST
                                </p>
                                <p class="mt-3 text-sm text-slate-500">{{ $notice->message }}</p>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-dashed border-cyan-100/30 p-8 text-center text-slate-500 md:col-span-2">
                                No admin notifications yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="panel-card p-6 lg:col-span-3">
                    <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Tracking Updates</p>
                    <h2 class="mt-2 text-2xl font-bold">Complaint Progress Completed</h2>

                    <div class="mt-6 grid gap-3 md:grid-cols-2">
                        @forelse ($trackingNotifications as $notice)
                            <div class="rounded-3xl border border-emerald-200/35 bg-slate-900/80 p-4">
                                <p class="font-semibold">{{ $notice->title }}</p>
                                <p class="mt-1 text-xs text-amber-300">
                                    {{ $notice->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} IST
                                </p>
                                <p class="mt-3 text-sm text-slate-500">{{ $notice->message }}</p>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-dashed border-cyan-100/30 p-8 text-center text-slate-500 md:col-span-2">
                                No completed tracking updates yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            <div class="panel-card p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Complaints</p>
                <h2 class="mt-2 text-2xl font-bold">New Complaint Alerts</h2>

                <div class="mt-6 grid gap-3">
                    @forelse ($newComplaints as $item)
                        <div class="rounded-3xl border border-rose-200/35 bg-slate-900/80 p-4">
                            <p class="font-semibold">{{ $item->employee_name }}</p>
                            <p class="mt-1 text-sm text-slate-500 capitalize">{{ str_replace('_', ' ', $item->feedback_place ?: 'Not selected') }}</p>
                            <p class="mt-3 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($item->improvements, 120) }}</p>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-dashed border-cyan-100/30 p-8 text-center text-slate-500">
                            No new complaint alerts.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="panel-card p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Maintenance</p>
                <h2 class="mt-2 text-2xl font-bold">System Maintenance Updates</h2>

                <div class="mt-6 grid gap-3">
                    @foreach ($systemUpdates->where('title', 'System maintenance update') as $notice)
                        <div class="rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4">
                            <p class="font-semibold">{{ $notice['title'] }}</p>
                            <p class="mt-1 text-xs text-amber-300">{{ $notice['time'] }}</p>
                            <p class="mt-3 text-sm text-slate-500">{{ $notice['message'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel-card p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Emergency</p>
                <h2 class="mt-2 text-2xl font-bold">Emergency Outage Notices</h2>

                <div class="mt-6 grid gap-3">
                    @foreach ($systemUpdates->where('title', 'Emergency outage notice') as $notice)
                        <div class="rounded-3xl border border-amber-200/35 bg-slate-900/80 p-4">
                            <p class="font-semibold">{{ $notice['title'] }}</p>
                            <p class="mt-1 text-xs text-amber-300">{{ $notice['time'] }}</p>
                            <p class="mt-3 text-sm text-slate-500">{{ $notice['message'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="panel-card p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Attention</p>
                <h2 class="mt-2 text-2xl font-bold">Low Rating Alerts</h2>

                <div class="mt-6 grid gap-3">
                    @forelse ($lowRatings as $item)
                        <div class="rounded-3xl border border-amber-200/35 bg-slate-900/80 p-4">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="font-semibold">{{ $item->employee_name }}</p>
                                    <p class="mt-1 text-sm text-slate-500 capitalize">{{ str_replace('_', ' ', $item->feedback_place ?: 'Not selected') }}</p>
                                </div>
                                <span class="w-fit rounded-full bg-amber-300 px-3 py-1 text-xs font-bold text-slate-950">
                                    Overall {{ $item->overall_satisfaction }}/5
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-dashed border-cyan-100/30 p-8 text-center text-slate-500">
                            No low-rating alerts right now.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="panel-card p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Activity</p>
                <h2 class="mt-2 text-2xl font-bold">Recent Submissions</h2>

                <div class="mt-6 grid gap-3">
                    @forelse ($recentFeedback as $item)
                        <div class="rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4">
                            <p class="font-semibold capitalize">{{ str_replace('_', ' ', $item->feedback_place ?: 'Not selected') }}</p>
                            <p class="mt-1 text-sm text-slate-500">
                                Submitted {{ $item->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} IST
                            </p>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-dashed border-cyan-100/30 p-8 text-center text-slate-500">
                            No recent submissions yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
