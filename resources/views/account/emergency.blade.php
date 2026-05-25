@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl">
        <div class="hero-card mb-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Emergency Control</p>
                    <h1 class="mt-3 text-4xl font-bold">Emergency Control Panel</h1>
                    <p class="mt-4 max-w-3xl text-sm text-slate-200">
                        Critical issue management for high-risk alerts, overload warnings, power cut reports, and emergency shutdown notifications.
                    </p>
                </div>
                <span class="w-fit rounded-full bg-rose-300 px-4 py-2 text-sm font-extrabold text-slate-950">
                    Critical Watch
                </span>
            </div>
        </div>

        <div class="mb-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($emergencyAlerts as $alert)
                <div class="panel-card p-6">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm uppercase tracking-[0.22em] text-amber-300">{{ $alert['type'] }}</p>
                        <span class="rounded-full px-3 py-1 text-xs font-bold text-slate-950 {{ $alert['color'] === 'rose' ? 'bg-rose-300' : ($alert['color'] === 'amber' ? 'bg-amber-300' : 'bg-cyan-300') }}">
                            {{ $alert['level'] }}
                        </span>
                    </div>
                    <p class="mt-4 text-sm text-slate-500">{{ $alert['message'] }}</p>
                    <p class="mt-4 text-xs font-semibold text-cyan-100">{{ $alert['time'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="panel-card p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">User Reports</p>
                <h2 class="mt-2 text-2xl font-bold">High-Risk Feedback</h2>

                <div class="mt-6 grid gap-3">
                    @forelse ($highRiskFeedback as $item)
                        <div class="rounded-3xl border border-rose-200/35 bg-slate-900/80 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="font-semibold">{{ $item->employee_name }}</p>
                                    <p class="mt-1 text-sm text-slate-500 capitalize">
                                        {{ str_replace('_', ' ', $item->feedback_place ?: 'Not selected') }} • {{ $item->department }}
                                    </p>
                                </div>
                                <span class="w-fit rounded-full bg-rose-300 px-3 py-1 text-xs font-bold text-slate-950">
                                    Safety {{ $item->safety_rating }}/5
                                </span>
                            </div>
                            <p class="mt-3 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($item->improvements, 140) }}</p>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-dashed border-cyan-100/30 p-8 text-center text-slate-500">
                            No high-risk feedback from your submissions right now.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="panel-card p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Action Checklist</p>
                <h2 class="mt-2 text-2xl font-bold">Emergency Response Steps</h2>

                <div class="mt-6 grid gap-3">
                    @foreach ([
                        'Verify the reported location and affected feedback place.',
                        'Check overload signs before restarting equipment.',
                        'Record any power cut duration and repeated outage patterns.',
                        'Use emergency shutdown only when safety or equipment risk is critical.',
                    ] as $step)
                        <div class="rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4">
                            <p class="font-semibold">{{ $step }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
