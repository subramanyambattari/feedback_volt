+@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl">
        <div class="hero-card mb-8 flex flex-col gap-5 p-8 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Responses</p>
                <h1 class="mt-2 text-4xl font-bold">Submitted Feedback Responses</h1>
                <p class="mt-3 max-w-2xl text-sm text-slate-200">
                    Review feedback details with submitted date and time shown in Indian Standard Time.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <span class="rounded-full border border-cyan-100/30 bg-slate-950/50 px-4 py-2 text-sm font-semibold text-cyan-100">
                    {{ $feedback->count() }} Responses
                </span>
                <a href="{{ route('feedback.create') }}" class="accent-button">
                    Add New Feedback
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="panel-card mb-6 border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="panel-card border-cyan-100/50 bg-slate-950/78 p-6">
            <div class="mb-6">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">Feedback Cards</h2>
                        <p class="mt-1 text-sm text-slate-500">Use the arrows to browse. Submitted time uses IST (UTC+05:30).</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" class="rounded-full border border-cyan-100/30 bg-slate-900/80 px-4 py-2 text-sm font-bold text-cyan-100 transition hover:bg-cyan-300 hover:text-slate-950" data-response-scroll="prev">
                            Prev
                        </button>
                        <button type="button" class="rounded-full border border-cyan-100/30 bg-slate-900/80 px-4 py-2 text-sm font-bold text-cyan-100 transition hover:bg-cyan-300 hover:text-slate-950" data-response-scroll="next">
                            Next
                        </button>
                        <span class="w-fit rounded-full bg-cyan-300 px-4 py-2 text-xs font-bold text-slate-950">
                            Asia/Kolkata
                        </span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto scroll-smooth pb-3" data-response-strip>
                <div class="flex gap-5">
                @forelse ($feedback as $item)
                    @php
                        $submittedAt = $item->created_at->copy()->timezone('Asia/Kolkata');
                    @endphp
                    <article class="w-[calc(100vw_-_4rem)] max-w-[38rem] shrink-0 rounded-3xl border border-cyan-100/25 bg-slate-900/85 p-5 shadow-[0_22px_60px_-44px_rgba(125,211,252,0.75)] transition hover:-translate-y-1 hover:border-amber-200/70 lg:w-[calc(50%_-_0.625rem)]">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-xl font-extrabold">{{ $item->employee_name }}</h3>
                                    <span class="rounded-full bg-cyan-300 px-3 py-1 text-xs font-bold capitalize text-slate-950">
                                        {{ str_replace('_', ' ', $item->feedback_place ?: 'Not selected') }}
                                    </span>
                                    <span class="rounded-full px-3 py-1 text-xs font-bold capitalize {{ $item->feedback_type === 'complaint' ? 'bg-rose-300 text-slate-950' : 'bg-emerald-300 text-slate-950' }}">
                                        {{ $item->feedback_type }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-slate-500">{{ $item->employee_id ?: 'No ID provided' }}</p>
                            </div>

                            <span class="w-fit rounded-full px-3 py-1 text-xs font-bold capitalize {{ $item->recommend_position === 'yes' ? 'bg-emerald-300 text-slate-950' : ($item->recommend_position === 'maybe' ? 'bg-amber-300 text-slate-950' : 'bg-rose-300 text-slate-950') }}">
                                {{ $item->recommend_position }}
                            </span>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl bg-slate-950/70 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Department</p>
                                <p class="mt-2 font-semibold">{{ $item->department }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-950/70 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Shift</p>
                                <p class="mt-2 font-semibold">{{ $item->shift_timing }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-950/70 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Feedback Date</p>
                                <p class="mt-2 font-semibold">{{ $item->feedback_date ? $item->feedback_date->format('d M Y') : 'Not selected' }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-950/70 p-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Submitted</p>
                                <p class="mt-2 font-semibold">{{ $submittedAt->format('d M Y') }}</p>
                                <p class="text-xs text-amber-300">{{ $submittedAt->format('h:i A') }} IST</p>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-3 text-sm sm:grid-cols-4">
                            <span class="rounded-2xl bg-slate-950/70 px-3 py-3 text-center">Safety<br><strong>{{ $item->safety_rating }}/5</strong></span>
                            <span class="rounded-2xl bg-slate-950/70 px-3 py-3 text-center">Comfort<br><strong>{{ $item->workstation_rating }}/5</strong></span>
                            <span class="rounded-2xl bg-slate-950/70 px-3 py-3 text-center">Facility<br><strong>{{ $item->equipment_rating }}/5</strong></span>
                            <span class="rounded-2xl bg-amber-300 px-3 py-3 text-center text-slate-950">Overall<br><strong>{{ $item->overall_satisfaction }}/5</strong></span>
                        </div>

                        <div class="mt-5 space-y-3 text-sm leading-6 text-slate-300">
                            <p><span class="font-semibold text-cyan-100">Strengths:</span> {{ $item->strengths }}</p>
                            <p><span class="font-semibold text-cyan-100">Improvements:</span> {{ $item->improvements }}</p>
                            @if ($item->additional_comments)
                                <p><span class="font-semibold text-cyan-100">Comments:</span> {{ $item->additional_comments }}</p>
                            @endif
                        </div>

                        <div class="mt-5 flex flex-wrap gap-2 border-t border-cyan-100/15 pt-4">
                            @if ($item->user_id === auth()->id())
                                <a href="{{ route('feedback.edit', $item) }}" class="rounded-full bg-cyan-300 px-5 py-2 text-sm font-bold text-slate-950 transition hover:bg-cyan-200">
                                    Edit
                                </a>
                                <form action="{{ route('feedback.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this feedback response?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-full bg-rose-300 px-5 py-2 text-sm font-bold text-slate-950 transition hover:bg-rose-200">
                                        Delete
                                    </button>
                                </form>
                            @elseif (auth()->user()->isAdmin())
                                <form action="{{ route('feedback.destroy', $item) }}" method="POST" onsubmit="return confirm('Admin delete this feedback response?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-full bg-rose-300 px-5 py-2 text-sm font-bold text-slate-950 transition hover:bg-rose-200">
                                        Admin Delete
                                    </button>
                                </form>
                            @else
                                <span class="rounded-full border border-cyan-100/20 px-4 py-2 text-xs font-semibold text-slate-500">View only</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="w-full rounded-3xl border border-dashed border-cyan-100/30 bg-slate-900/70 px-4 py-12 text-center text-slate-500">
                        No feedback submitted yet.
                    </div>
                @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        const responseStrip = document.querySelector('[data-response-strip]');
        const responseButtons = document.querySelectorAll('[data-response-scroll]');

        if (responseStrip) {
            const scrollResponses = (direction = 1) => {
                const card = responseStrip.querySelector('article');
                const gap = 20;
                const perView = window.matchMedia('(min-width: 1024px)').matches ? 2 : 1;
                const distance = card ? ((card.offsetWidth + gap) * perView) : responseStrip.clientWidth;
                const maxScroll = responseStrip.scrollWidth - responseStrip.clientWidth;

                if (maxScroll <= 0) {
                    return;
                }

                if (direction > 0 && responseStrip.scrollLeft + distance >= maxScroll) {
                    responseStrip.scrollTo({ left: maxScroll, behavior: 'smooth' });
                    return;
                }

                if (direction < 0 && responseStrip.scrollLeft - distance <= 0) {
                    responseStrip.scrollTo({ left: 0, behavior: 'smooth' });
                    return;
                }

                responseStrip.scrollBy({ left: distance * direction, behavior: 'smooth' });
            };

            responseButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    scrollResponses(button.dataset.responseScroll === 'prev' ? -1 : 1);
                });
            });
        }
    </script>
@endsection
