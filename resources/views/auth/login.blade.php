@extends('layouts.app')

@section('content')
    <div class="page-stage page-bg page-bg-login auth-shell pt-6">
        <div class="flow-ribbon flow-ribbon-one"></div>
        <div class="flow-ribbon flow-ribbon-two"></div>
        <div class="page-orb-amber animate-orbit -left-16 top-4 h-36 w-36"></div>
        <div class="page-orb-sky animate-drift -right-12 top-20 h-44 w-44"></div>
        <div class="page-orb-slate animate-pulse-soft bottom-0 left-1/3 h-32 w-32"></div>

        <div class="auth-card animate-card-float overflow-hidden">
            <div class="animate-drift absolute -right-10 top-8 h-32 w-32 rounded-full bg-amber-200/25 blur-3xl"></div>
            <p class="animate-rise-in text-sm uppercase tracking-[0.3em] text-amber-600 delay-150">Login</p>
            <h1 class="animate-rise-in mt-3 text-3xl font-bold text-slate-900 delay-300">Sign in to your account</h1>
            <p class="animate-rise-in mt-2 text-sm text-slate-600 delay-450">Use your registered email and password to continue.</p>

            @if ($errors->any())
                <div class="animate-rise-in mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 delay-450">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.attempt') }}" method="POST" class="animate-rise-in mt-8 grid gap-5 delay-600">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="field-input" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Password</label>
                    <input type="password" name="password" class="field-input" required>
                </div>
                <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" value="1">
                    <span>Remember me</span>
                </label>
                <button type="submit" class="primary-button">Login</button>
            </form>
        </div>
    </div>
@endsection
