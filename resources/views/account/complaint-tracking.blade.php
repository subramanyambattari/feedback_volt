@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl">
        <div class="hero-card mb-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Complaint Tracking</p>
                    <h1 class="mt-3 text-4xl font-bold">Track Complaint Progress</h1>
                    <p class="mt-4 max-w-3xl text-sm text-slate-200">
                        Follow each complaint from submitted to resolved with technician assignment and expected resolution details.
                    </p>
                </div>
                <a href="{{ route('feedback.create') }}" class="accent-button">New Complaint</a>
            </div>
        </div>

        @if (session('success'))
            <div class="panel-card mb-6 border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6">
            @forelse ($feedback as $item)
                <article class="panel-card border-cyan-100/50 bg-slate-950/80 p-6">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Complaint ID</p>
                            <h2 class="mt-2 text-2xl font-bold">{{ $item->tracking_id }}</h2>
                            <p class="mt-2 text-sm text-slate-500 capitalize">
                                {{ str_replace('_', ' ', $item->feedback_place ?: 'Not selected') }} complaint by {{ $item->employee_name }}
                            </p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-3 lg:min-w-[34rem]">
                            <div class="rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Status</p>
                                <p class="mt-2 font-bold text-cyan-100">{{ $item->tracking_status }}</p>
                            </div>
                            <div class="rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Technician</p>
                                <p class="mt-2 font-bold text-cyan-100">{{ $item->assigned_technician }}</p>
                            </div>
                            <div class="rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Resolution Date</p>
                                <p class="mt-2 font-bold text-cyan-100">{{ $item->resolution_date }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 grid gap-4 md:grid-cols-4">
                        @foreach ($item->tracking_steps as $index => $step)
                            @php
                                $isComplete = $index <= $item->tracking_current_step;
                                $isCurrent = $index === $item->tracking_current_step;
                            @endphp
                            <div class="relative rounded-3xl border p-5 transition {{ $isComplete ? 'border-cyan-200/70 bg-cyan-950/70 shadow-[0_0_34px_-12px_rgba(34,211,238,0.9)]' : 'border-cyan-100/18 bg-slate-900/60' }}">
                                @if (! $loop->last)
                                    <span class="pointer-events-none absolute left-[calc(100%-0.5rem)] top-1/2 hidden h-1 w-8 -translate-y-1/2 rounded-full {{ $isComplete ? 'bg-cyan-300 shadow-[0_0_22px_rgba(34,211,238,0.9)]' : 'bg-slate-700' }} md:block"></span>
                                @endif
                                <div class="flex items-center gap-3">
                                    <span class="grid h-10 w-10 place-items-center rounded-full {{ $isComplete ? 'bg-cyan-300 text-slate-950 shadow-[0_0_26px_rgba(34,211,238,0.9)]' : 'bg-slate-800 text-slate-400' }}">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <p class="font-bold">{{ $step }}</p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $isCurrent ? 'Current stage' : ($isComplete ? 'Completed' : 'Waiting') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (auth()->user()->isTechnician() && $item->assigned_technician_id === auth()->id())
                        <div class="mt-6 border-t border-cyan-100/15 pt-5">
                            @if ($item->repair_progress === 'completed')
                                <span class="inline-flex rounded-full bg-emerald-300 px-5 py-2 text-sm font-extrabold text-slate-950">
                                    Task Completed
                                </span>
                            @else
                                <form action="{{ route('complaints.complete', $item) }}" method="POST" onsubmit="return confirm('Mark this assigned complaint as completed? Admin will be notified.')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="primary-button">
                                        Mark Task Completed
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </article>
            @empty
                <div class="panel-card p-10 text-center">
                    <h2 class="text-2xl font-bold">No complaints to track yet.</h2>
                    <p class="mt-3 text-sm text-slate-500">Submit feedback as a complaint, then its status timeline will appear here. Suggestions stay in responses only.</p>
                    <a href="{{ route('feedback.create') }}" class="accent-button mt-6">Submit Feedback</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
