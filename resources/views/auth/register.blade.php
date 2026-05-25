@extends('layouts.app')

@section('content')
    <div class="page-stage page-bg page-bg-register auth-shell pt-6">
        <div class="flow-ribbon flow-ribbon-one"></div>
        <div class="flow-ribbon flow-ribbon-two"></div>
        <div class="page-orb-amber animate-orbit -right-12 top-0 h-40 w-40"></div>
        <div class="page-orb-sky animate-drift -left-12 top-24 h-48 w-48"></div>
        <div class="page-orb-slate animate-pulse-soft bottom-6 right-1/4 h-32 w-32"></div>

        <div class="auth-card animate-card-float overflow-hidden">
            <div class="animate-drift absolute -left-10 bottom-10 h-32 w-32 rounded-full bg-sky-200/30 blur-3xl"></div>
            <p class="animate-rise-in text-sm uppercase tracking-[0.3em] text-amber-600 delay-150">Registration</p>
            <h1 class="animate-rise-in mt-3 text-3xl font-bold text-slate-900 delay-300">Create your account</h1>
            <p class="animate-rise-in mt-2 text-sm text-slate-600 delay-450">Register first, then log in and submit feedback.</p>

            @if ($errors->any())
                <div class="animate-rise-in mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 delay-450">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" class="animate-rise-in mt-8 grid gap-5 delay-600">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-semibold">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="field-input" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="field-input" required>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-semibold">Register As</label>
                    <div class="grid gap-3 sm:grid-cols-3">
                        <label class="chip-option justify-center">
                            <input type="radio" name="role" value="user" {{ old('role', 'user') === 'user' ? 'checked' : '' }} required>
                            <span>User</span>
                        </label>
                        <label class="chip-option justify-center">
                            <input type="radio" name="role" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }} required>
                            <span>Admin</span>
                        </label>
                        <label class="chip-option justify-center">
                            <input type="radio" name="role" value="technician" {{ old('role') === 'technician' ? 'checked' : '' }} required>
                            <span>Technician</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Password</label>
                    <input type="password" name="password" class="field-input" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="field-input" required>
                </div>
                <button type="submit" class="primary-button">Registration</button>
            </form>
        </div>
    </div>
@endsection
