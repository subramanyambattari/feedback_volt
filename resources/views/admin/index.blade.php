@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl">
        <div class="hero-card mb-8">
            <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Admin Panel</p>
            <h1 class="mt-3 text-4xl font-bold">Admin Control Hub</h1>
            <p class="mt-4 max-w-3xl text-sm text-slate-200">
                Open dashboard, responses, technician assignments, notifications, profile, and broadcast updates.
            </p>
        </div>

        @if (session('admin_success'))
            <div class="panel-card mb-6 border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700">
                {{ session('admin_success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="panel-card mb-6 border-rose-200 bg-rose-50 px-5 py-4 text-rose-700">
                <p class="font-semibold">Please fix the following errors:</p>
                <ul class="mt-2 list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('dashboard') }}" class="panel-card block p-6 transition hover:-translate-y-1 hover:border-amber-200/70">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Page</p>
                <h2 class="mt-3 text-2xl font-bold">Dashboard</h2>
                <p class="mt-2 text-sm text-slate-500">View overview and feedback summaries.</p>
            </a>
            <a href="{{ route('feedback.index') }}" class="panel-card block p-6 transition hover:-translate-y-1 hover:border-amber-200/70">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Page</p>
                <h2 class="mt-3 text-2xl font-bold">Responses</h2>
                <p class="mt-2 text-sm text-slate-500">Review submitted responses.</p>
            </a>
            <a href="{{ route('notifications') }}" class="panel-card block p-6 transition hover:-translate-y-1 hover:border-amber-200/70">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Page</p>
                <h2 class="mt-3 text-2xl font-bold">Notifications</h2>
                <p class="mt-2 text-sm text-slate-500">Check user alerts and admin notices.</p>
            </a>
            <a href="{{ route('admin.technicians') }}" class="panel-card block p-6 transition hover:-translate-y-1 hover:border-amber-200/70">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Page</p>
                <h2 class="mt-3 text-2xl font-bold">Technicians</h2>
                <p class="mt-2 text-sm text-slate-500">Assign workers and update repair progress.</p>
            </a>
            <a href="{{ route('profile') }}" class="panel-card block p-6 transition hover:-translate-y-1 hover:border-amber-200/70">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Page</p>
                <h2 class="mt-3 text-2xl font-bold">Profile</h2>
                <p class="mt-2 text-sm text-slate-500">Manage admin account details.</p>
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <form action="{{ route('admin.notifications.send') }}" method="POST" class="panel-card grid gap-5 p-6">
                @csrf
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Broadcast</p>
                    <h2 class="mt-2 text-2xl font-bold">Send Notification To All Users</h2>
                    <p class="mt-2 text-sm text-slate-500">This message will appear on every user's Notifications page.</p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Notification Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="field-input" placeholder="System maintenance update" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Notification Text</label>
                    <textarea name="message" rows="6" class="field-input" placeholder="Write notification text for all users..." required>{{ old('message') }}</textarea>
                </div>

                <div>
                    <button type="submit" class="primary-button">Send Notification</button>
                </div>
            </form>

            <div class="panel-card p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Recent</p>
                <h2 class="mt-2 text-2xl font-bold">Sent Notifications</h2>

                <div class="mt-6 grid gap-3">
                    @forelse ($latestNotices as $notice)
                        <div class="rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4">
                            <p class="font-semibold">{{ $notice->title }}</p>
                            <p class="mt-1 text-xs text-amber-300">
                                {{ $notice->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} IST
                            </p>
                            <p class="mt-3 text-sm text-slate-500">{{ $notice->message }}</p>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-dashed border-cyan-100/30 p-8 text-center text-slate-500">
                            No admin notifications sent yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-5">
            <div class="panel-card p-6">
                <p class="text-sm text-slate-500">Users</p>
                <p class="mt-3 text-4xl font-extrabold">{{ $reports['users'] }}</p>
            </div>
            <div class="panel-card p-6">
                <p class="text-sm text-slate-500">Technicians</p>
                <p class="mt-3 text-4xl font-extrabold">{{ $reports['technicians'] }}</p>
            </div>
            <div class="panel-card p-6">
                <p class="text-sm text-slate-500">Responses</p>
                <p class="mt-3 text-4xl font-extrabold">{{ $reports['feedback'] }}</p>
            </div>
            <div class="panel-card p-6">
                <p class="text-sm text-slate-500">Low Safety</p>
                <p class="mt-3 text-4xl font-extrabold">{{ $reports['low_safety'] }}</p>
            </div>
            <div class="panel-card p-6">
                <p class="text-sm text-slate-500">Notices Sent</p>
                <p class="mt-3 text-4xl font-extrabold">{{ $reports['notices'] }}</p>
            </div>
        </div>
    </div>
@endsection
