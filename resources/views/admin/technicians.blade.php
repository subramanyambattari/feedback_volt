@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl">
        <div class="hero-card mb-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Technician Management</p>
                    <h1 class="mt-3 text-4xl font-bold">Assign Workers</h1>
                    <p class="mt-4 max-w-3xl text-sm text-slate-200">
                        Review technician availability, current tasks, and repair progress for active complaints.
                    </p>
                </div>
                <a href="#add-technician" class="accent-button">Add Technician</a>
            </div>
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

        <form id="add-technician" action="{{ route('admin.technicians.store') }}" method="POST" class="panel-card mb-8 grid gap-4 p-6 md:grid-cols-2 xl:grid-cols-4">
            @csrf
            <div class="md:col-span-2 xl:col-span-4">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Create Worker</p>
                <h2 class="mt-2 text-2xl font-bold">Add Technician Account</h2>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="field-input" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="field-input" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Password</label>
                <input type="password" name="password" class="field-input" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" class="field-input" required>
            </div>
            <div class="md:col-span-2 xl:col-span-4">
                <button type="submit" class="primary-button">Create Technician</button>
            </div>
        </form>

        <div class="mb-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($technicians as $technician)
                @php
                    $availability = $technician->assigned_tasks_count === 0
                        ? ['label' => 'Available', 'class' => 'bg-emerald-300 text-slate-950']
                        : ($technician->assigned_tasks_count < 3
                            ? ['label' => 'On Duty', 'class' => 'bg-amber-300 text-slate-950']
                            : ['label' => 'Busy', 'class' => 'bg-rose-300 text-slate-950']);
                @endphp
                <article class="panel-card p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Technician</p>
                            <h2 class="mt-2 text-2xl font-bold">{{ $technician->name }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $technician->email }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-extrabold {{ $availability['class'] }}">
                            {{ $availability['label'] }}
                        </span>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-slate-950/70 p-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Current Tasks</p>
                            <p class="mt-2 text-3xl font-extrabold">{{ $technician->assigned_tasks_count }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-950/70 p-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Availability</p>
                            <p class="mt-2 font-bold">{{ $availability['label'] }}</p>
                        </div>
                    </div>
                </article>
            @empty
                <div class="panel-card p-8 text-center md:col-span-2 xl:col-span-3">
                    <h2 class="text-2xl font-bold">No technicians registered yet.</h2>
                    <p class="mt-3 text-sm text-slate-500">Use the registration page and select Technician to add worker accounts.</p>
                </div>
            @endforelse
        </div>

        <div class="panel-card p-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Assignments</p>
                    <h2 class="mt-2 text-2xl font-bold">Complaint Repair Progress</h2>
                </div>
                <a href="{{ route('feedback.index') }}" class="nav-link nav-link-active">Open Responses</a>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-[0.18em] text-amber-300">
                        <tr>
                            <th class="px-4 py-3">Complaint</th>
                            <th class="px-4 py-3">Issue</th>
                            <th class="px-4 py-3">Technician</th>
                            <th class="px-4 py-3">Progress</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyan-100/10">
                        @forelse ($complaints as $complaint)
                            <tr>
                                <td class="px-4 py-4">
                                    <p class="font-bold">CMP-{{ $complaint->created_at->format('Y') }}-{{ str_pad((string) $complaint->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $complaint->employee_name }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="capitalize">{{ str_replace('_', ' ', $complaint->feedback_place ?: 'Not selected') }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($complaint->improvements, 70) }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <form id="assign-{{ $complaint->id }}" action="{{ route('admin.technicians.assign', $complaint) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="assigned_technician_id" class="field-input min-w-48">
                                            <option value="">Unassigned</option>
                                            @foreach ($technicians as $technician)
                                                <option value="{{ $technician->id }}" {{ $complaint->assigned_technician_id === $technician->id ? 'selected' : '' }}>
                                                    {{ $technician->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="px-4 py-4">
                                    <select name="repair_progress" form="assign-{{ $complaint->id }}" class="field-input min-w-44">
                                        @foreach ($progressOptions as $value => $label)
                                            <option value="{{ $value }}" {{ $complaint->repair_progress === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-4">
                                    <button type="submit" form="assign-{{ $complaint->id }}" class="primary-button py-2">
                                        Save
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-slate-500">No complaints available for assignment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
