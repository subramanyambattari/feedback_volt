@extends('layouts.app')

@section('content')
<section class="page-stage page-bg page-bg-home grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">

    <div class="flow-ribbon flow-ribbon-one"></div>
    <div class="flow-ribbon flow-ribbon-two"></div>

    <div class="page-orb-amber animate-orbit absolute -top-10 left-[8%] h-40 w-40"></div>
    <div class="page-orb-sky animate-drift absolute right-[12%] top-24 h-52 w-52"></div>
    <div class="page-orb-slate animate-pulse-soft absolute bottom-8 left-[36%] h-44 w-44"></div>

    <!-- HERO SECTION -->
    <div class="hero-card animate-rise-in animate-card-float relative overflow-hidden">

        <div class="animate-drift absolute -right-10 top-6 h-36 w-36 rounded-full bg-amber-300/20 blur-3xl"></div>

        <div class="animate-pulse-soft absolute -left-10 bottom-0 h-28 w-28 rounded-full bg-sky-200/10 blur-3xl"></div>

        <p class="text-sm uppercase tracking-[0.3em] text-amber-300">
            Home Page
        </p>

        <h1 class="mt-4 text-4xl sm:text-5xl font-extrabold leading-tight text-white">
            Welcome to the Power Supply Position Feedback Portal
        </h1>

        <p class="mt-5 text-base text-slate-200 leading-7">
            This project helps teams collect clear, organized feedback
            about the power supply position.
            Users can register, log in, submit feedback,
            and review responses in one place.
        </p>

        <div class="mt-8 flex flex-wrap gap-4">

            @guest
                <a href="{{ route('login') }}" class="accent-button">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="primary-button border border-white/20">
                    Registration
                </a>
            @else

                <a href="{{ route('feedback.create') }}"
                   class="accent-button">
                    Submit Feedback
                </a>

                <a href="{{ route('feedback.index') }}"
                   class="primary-button border border-white/20">
                    View Responses
                </a>

            @endguest

        </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="grid gap-5">

        <div class="panel-card p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-300">
                Purpose
            </p>

            <h2 class="mt-3 text-2xl font-bold text-white">
                A simple system for better workplace insight
            </h2>

            <p class="mt-3 text-sm leading-6 text-slate-200">
                Capture employee views on safety,
                equipment, workstation quality,
                and overall satisfaction for the
                power supply role.
            </p>
        </div>

        <div class="panel-card grid gap-4 p-6 sm:grid-cols-3">

            <div class="feature-tile">
                <p class="text-3xl font-extrabold text-cyan-300">
                    01
                </p>

                <p class="mt-2 text-sm font-semibold text-white">
                    Register
                </p>

                <p class="mt-1 text-sm text-slate-200">
                    Create a personal account to access the system.
                </p>
            </div>

            <div class="feature-tile">
                <p class="text-3xl font-extrabold text-cyan-300">
                    02
                </p>

                <p class="mt-2 text-sm font-semibold text-white">
                    Login
                </p>

                <p class="mt-1 text-sm text-slate-200">
                    Enter securely and continue to the feedback form.
                </p>
            </div>

            <div class="feature-tile">
                <p class="text-3xl font-extrabold text-cyan-300">
                    03
                </p>

                <p class="mt-2 text-sm font-semibold text-white">
                    Respond
                </p>

                <p class="mt-1 text-sm text-slate-200">
                    Share insights that help improve the position.
                </p>
            </div>

        </div>
    </div>

</section>
@endsection