@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl">
        <div class="hero-card mb-8">
            <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Profile</p>
            <h1 class="mt-3 text-4xl font-bold">{{ $user->name }}</h1>
            <p class="mt-4 max-w-3xl text-sm text-slate-200">
                View your account details, update your email or password, and review your feedback activity.
            </p>
        </div>

        @if (session('profile_success') || session('password_success'))
            <div class="panel-card mb-6 border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700">
                {{ session('profile_success') ?? session('password_success') }}
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

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="panel-card p-6 lg:col-span-1">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-cyan-300 text-3xl font-extrabold text-slate-950">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="mt-5 text-2xl font-bold">{{ $user->name }}</h2>
                <p class="mt-2 text-sm text-slate-500">{{ $user->email }}</p>
                <div class="mt-6 rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-amber-300">Joined</p>
                    <p class="mt-2 font-semibold">{{ $user->created_at->timezone('Asia/Kolkata')->format('d M Y') }}</p>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-3 lg:col-span-2">
                <div class="panel-card p-6">
                    <p class="text-sm text-slate-500">My Feedback</p>
                    <p class="mt-3 text-4xl font-extrabold">{{ $feedbackStats->total ?? 0 }}</p>
                </div>
                <div class="panel-card p-6">
                    <p class="text-sm text-slate-500">Avg Safety</p>
                    <p class="mt-3 text-4xl font-extrabold">{{ number_format($feedbackStats->safety ?? 0, 1) }}/5</p>
                </div>
                <div class="panel-card p-6">
                    <p class="text-sm text-slate-500">Avg Satisfaction</p>
                    <p class="mt-3 text-4xl font-extrabold">{{ number_format($feedbackStats->satisfaction ?? 0, 1) }}/5</p>
                </div>

                <div class="panel-card p-6 md:col-span-3">
                    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Recent</p>
                            <h2 class="mt-2 text-2xl font-bold">Your Latest Feedback</h2>
                        </div>
                        <a href="{{ route('feedback.index') }}" class="nav-link nav-link-active">Open Responses</a>
                    </div>

                    <div class="grid gap-3">
                        @forelse ($latestFeedback as $item)
                            <div class="rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="font-semibold capitalize">{{ str_replace('_', ' ', $item->feedback_place ?: 'Not selected') }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $item->department }} • {{ $item->shift_timing }}</p>
                                    </div>
                                    <span class="w-fit rounded-full bg-amber-300 px-3 py-1 text-xs font-bold text-slate-950">
                                        {{ $item->overall_satisfaction }}/5
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-dashed border-cyan-100/30 p-8 text-center text-slate-500">
                                You have not submitted feedback yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <form action="{{ route('profile.update') }}" method="POST" class="panel-card grid gap-5 p-6">
                @csrf
                @method('PATCH')

                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Edit</p>
                    <h2 class="mt-2 text-2xl font-bold">Update Account Details</h2>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="field-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="field-input" required>
                </div>

                <div>
                    <button type="submit" class="primary-button">Update Profile</button>
                </div>
            </form>

            <form action="{{ route('profile.password.update') }}" method="POST" class="panel-card grid gap-5 p-6">
                @csrf
                @method('PATCH')

                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Security</p>
                    <h2 class="mt-2 text-2xl font-bold">Update Password</h2>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Current Password</label>
                    <input type="password" name="current_password" class="field-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">New Password</label>
                    <input type="password" name="password" class="field-input" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="field-input" required>
                </div>

                <div>
                    <button type="submit" class="accent-button">Update Password</button>
                </div>
            </form>
        </div>
    </div>
@endsection
