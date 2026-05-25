@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl">
        <div class="hero-card mb-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Dashboard</p>
                    <h1 class="mt-3 text-4xl font-bold">Welcome, {{ auth()->user()->name }}</h1>
                    <p class="mt-4 max-w-3xl text-sm text-slate-200">
                        Track feedback, view response trends, and move quickly to new submissions or response reviews.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('feedback.create') }}" class="accent-button">Add Feedback</a>
                    <a href="{{ route('feedback.index') }}" class="primary-button">View Responses</a>
                </div>
            </div>
        </div>

        <div class="mb-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="panel-card border-cyan-100/50 bg-slate-950/80 p-6">
                <p class="text-sm text-slate-500">Total Responses</p>
                <p class="mt-3 text-4xl font-extrabold">{{ $totalFeedback }}</p>
            </div>
            <div class="panel-card border-cyan-100/50 bg-slate-950/80 p-6">
                <p class="text-sm text-slate-500">Avg Safety</p>
                <p class="mt-3 text-4xl font-extrabold">{{ number_format($averages->safety ?? 0, 1) }}/5</p>
            </div>
            <div class="panel-card border-cyan-100/50 bg-slate-950/80 p-6">
                <p class="text-sm text-slate-500">Avg Equipment</p>
                <p class="mt-3 text-4xl font-extrabold">{{ number_format($averages->equipment ?? 0, 1) }}/5</p>
            </div>
            <div class="panel-card border-cyan-100/50 bg-slate-950/80 p-6">
                <p class="text-sm text-slate-500">Avg Satisfaction</p>
                <p class="mt-3 text-4xl font-extrabold">{{ number_format($averages->satisfaction ?? 0, 1) }}/5</p>
            </div>
        </div>

        <div class="panel-card mb-8 border-cyan-100/50 bg-slate-950/80 p-6">
            <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-amber-300">All Feedback</p>
                    <h2 class="mt-2 text-2xl font-bold">Browse Other Feedback</h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Auto-scrolls every 5 seconds. Drag sideways or use the controls for manual browsing.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" class="rounded-full border border-cyan-100/30 bg-slate-900/80 px-4 py-2 text-sm font-bold text-cyan-100 transition hover:bg-cyan-300 hover:text-slate-950" data-scroll-feedback="prev">
                        Prev
                    </button>
                    <button type="button" class="rounded-full border border-cyan-100/30 bg-slate-900/80 px-4 py-2 text-sm font-bold text-cyan-100 transition hover:bg-cyan-300 hover:text-slate-950" data-scroll-feedback="next">
                        Next
                    </button>
                    <a href="{{ route('feedback.index') }}" class="nav-link nav-link-active">Open Full Table</a>
                </div>
            </div>

            <div class="overflow-x-auto scroll-smooth pb-3" data-feedback-strip>
                <div class="flex min-w-max gap-4">
                    @forelse ($allFeedback as $item)
                        <article class="w-80 shrink-0 rounded-3xl border border-cyan-100/25 bg-slate-900/85 p-5 shadow-[0_22px_60px_-42px_rgba(125,211,252,0.7)] transition hover:-translate-y-1 hover:border-amber-200/70 hover:bg-slate-900">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-bold">{{ $item->employee_name }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">{{ $item->employee_id ?: 'No ID provided' }}</p>
                                </div>
                                <span class="rounded-full bg-cyan-300 px-3 py-1 text-xs font-bold capitalize text-slate-950">
                                    {{ $item->recommend_position }}
                                </span>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                                <div class="rounded-2xl bg-slate-950/75 p-3">
                                    <p class="text-slate-500">Place</p>
                                    <p class="mt-1 font-semibold capitalize">{{ str_replace('_', ' ', $item->feedback_place ?: 'Not selected') }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-950/75 p-3">
                                    <p class="text-slate-500">Department</p>
                                    <p class="mt-1 font-semibold">{{ $item->department }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-950/75 p-3">
                                    <p class="text-slate-500">Shift</p>
                                    <p class="mt-1 font-semibold">{{ $item->shift_timing }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-950/75 p-3">
                                    <p class="text-slate-500">Safety</p>
                                    <p class="mt-1 font-semibold">{{ $item->safety_rating }}/5</p>
                                </div>
                                <div class="rounded-2xl bg-amber-300 p-3 text-slate-950">
                                    <p class="text-slate-700">Satisfaction</p>
                                    <p class="mt-1 font-extrabold">{{ $item->overall_satisfaction }}/5</p>
                                </div>
                            </div>

                            <p class="mt-4 text-sm font-semibold text-cyan-100">Strengths</p>
                            <p class="mt-1 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($item->strengths, 95) }}</p>

                            <p class="mt-4 text-sm font-semibold text-cyan-100">Improvements</p>
                            <p class="mt-1 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($item->improvements, 95) }}</p>
                        </article>
                    @empty
                        <div class="w-full rounded-3xl border border-dashed border-cyan-100/30 bg-slate-900/80 p-8 text-center text-slate-500">
                            No feedback is available to browse yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="panel-card border-cyan-100/50 bg-slate-950/80 p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Departments</p>
                <h2 class="mt-2 text-2xl font-bold">Top Feedback Areas</h2>

                <div class="mt-6 grid gap-3">
                    @forelse ($departmentLeaders as $department)
                        <div class="flex items-center justify-between rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4">
                            <div>
                                <p class="font-semibold">{{ $department->department }}</p>
                                <p class="text-sm text-slate-500">{{ $department->total }} responses</p>
                            </div>
                            <span class="text-lg font-bold">{{ number_format($department->average_rating, 1) }}/5</span>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-dashed border-cyan-100/30 bg-slate-900/80 p-8 text-center text-slate-500">
                            Department insights will appear after feedback is submitted.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="panel-card border-cyan-100/50 bg-slate-950/80 p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Follow Up</p>
                <h2 class="mt-2 text-2xl font-bold">Needs Attention</h2>

                <div class="mt-6 grid gap-3">
                    @forelse ($needsAttention as $item)
                        <div class="rounded-3xl border border-amber-200/35 bg-slate-900/80 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <p class="font-semibold">{{ $item->employee_name }}</p>
                                <span class="rounded-full bg-amber-300 px-3 py-1 text-xs font-bold text-slate-950">
                                    Safety {{ $item->safety_rating }}/5
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($item->improvements, 120) }}</p>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-dashed border-cyan-100/30 bg-slate-900/80 p-8 text-center text-slate-500">
                            No low-rating feedback needs attention right now.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        const feedbackStrip = document.querySelector('[data-feedback-strip]');
        const scrollButtons = document.querySelectorAll('[data-scroll-feedback]');

        if (feedbackStrip) {
            const scrollOneCard = (direction = 1) => {
                const card = feedbackStrip.querySelector('article');
                const distance = card ? card.offsetWidth + 16 : 340;
                const maxScroll = feedbackStrip.scrollWidth - feedbackStrip.clientWidth;

                if (maxScroll <= 0) {
                    return;
                }

                if (direction > 0 && feedbackStrip.scrollLeft + distance >= maxScroll) {
                    feedbackStrip.scrollTo({ left: 0, behavior: 'smooth' });
                    return;
                }

                if (direction < 0 && feedbackStrip.scrollLeft - distance <= 0) {
                    feedbackStrip.scrollTo({ left: maxScroll, behavior: 'smooth' });
                    return;
                }

                feedbackStrip.scrollBy({ left: distance * direction, behavior: 'smooth' });
            };

            let feedbackAutoScroll = setInterval(() => scrollOneCard(1), 5000);

            const resetAutoScroll = () => {
                clearInterval(feedbackAutoScroll);
                feedbackAutoScroll = setInterval(() => scrollOneCard(1), 5000);
            };

            scrollButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    scrollOneCard(button.dataset.scrollFeedback === 'prev' ? -1 : 1);
                    resetAutoScroll();
                });
            });

            feedbackStrip.addEventListener('pointerdown', resetAutoScroll);
            feedbackStrip.addEventListener('wheel', resetAutoScroll, { passive: true });
        }
    </script>
@endsection
