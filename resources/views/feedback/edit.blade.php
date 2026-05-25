@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl">
        <div class="hero-card mb-8">
            <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Edit Response</p>
            <h1 class="mt-3 text-4xl font-bold">Update Feedback</h1>
            <p class="mt-4 max-w-3xl text-sm text-slate-200">
                Update your submitted feedback details and save the latest response.
            </p>
        </div>

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

        <form action="{{ route('feedback.update', $feedback) }}" method="POST" class="panel-card grid gap-6 p-8 md:grid-cols-2">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-semibold">Name</label>
                <input type="text" name="employee_name" value="{{ old('employee_name', $feedback->employee_name) }}" class="field-input" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">ID / Reference Number</label>
                <input type="text" name="employee_id" value="{{ old('employee_id', $feedback->employee_id) }}" class="field-input">
            </div>

            <div class="md:col-span-2">
                <label class="mb-3 block text-sm font-semibold">Submission Type</label>
                <div class="grid gap-3 sm:grid-cols-2">
                    <label class="chip-option justify-center">
                        <input type="radio" name="feedback_type" value="complaint" {{ old('feedback_type', $feedback->feedback_type) === 'complaint' ? 'checked' : '' }} required>
                        <span>Complaint</span>
                    </label>
                    <label class="chip-option justify-center">
                        <input type="radio" name="feedback_type" value="suggestion" {{ old('feedback_type', $feedback->feedback_type) === 'suggestion' ? 'checked' : '' }} required>
                        <span>Suggestion</span>
                    </label>
                </div>
                <p class="mt-2 text-xs text-slate-500">Only complaints appear on complaint tracking.</p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Feedback Place</label>
                <select name="feedback_place" class="field-input" required>
                    @foreach ([
                        'home' => 'Home',
                        'office' => 'Office',
                        'bus' => 'Bus',
                        'airport' => 'Airport',
                        'train_station' => 'Train Station',
                        'school' => 'School',
                        'hospital' => 'Hospital',
                        'market' => 'Market',
                        'public_place' => 'Public Place',
                        'other' => 'Other',
                    ] as $value => $label)
                        <option value="{{ $value }}" {{ old('feedback_place', $feedback->feedback_place) === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Feedback Date</label>
                <input type="date" name="feedback_date" value="{{ old('feedback_date', optional($feedback->feedback_date)->toDateString()) }}" class="field-input" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Department / Area</label>
                <input type="text" name="department" value="{{ old('department', $feedback->department) }}" class="field-input" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Shift / Visit Time</label>
                <input type="text" name="shift_timing" value="{{ old('shift_timing', $feedback->shift_timing) }}" class="field-input" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Safety Rating (1-5)</label>
                <input type="number" min="1" max="5" name="safety_rating" value="{{ old('safety_rating', $feedback->safety_rating) }}" class="field-input" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Comfort / Workstation Rating (1-5)</label>
                <input type="number" min="1" max="5" name="workstation_rating" value="{{ old('workstation_rating', $feedback->workstation_rating) }}" class="field-input" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Facility / Equipment Rating (1-5)</label>
                <input type="number" min="1" max="5" name="equipment_rating" value="{{ old('equipment_rating', $feedback->equipment_rating) }}" class="field-input" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Overall Satisfaction (1-5)</label>
                <input type="number" min="1" max="5" name="overall_satisfaction" value="{{ old('overall_satisfaction', $feedback->overall_satisfaction) }}" class="field-input" required>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold">Strengths</label>
                <textarea name="strengths" rows="4" class="field-input" required>{{ old('strengths', $feedback->strengths) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold">Improvements</label>
                <textarea name="improvements" rows="4" class="field-input" required>{{ old('improvements', $feedback->improvements) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold">Additional Comments</label>
                <textarea name="additional_comments" rows="3" class="field-input">{{ old('additional_comments', $feedback->additional_comments) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="mb-3 block text-sm font-semibold">Would you recommend it?</label>
                <div class="flex flex-wrap gap-4">
                    @foreach (['yes' => 'Yes', 'no' => 'No', 'maybe' => 'Maybe'] as $value => $label)
                        <label class="chip-option">
                            <input type="radio" name="recommend_position" value="{{ $value }}" {{ old('recommend_position', $feedback->recommend_position) === $value ? 'checked' : '' }} required>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-wrap gap-3 md:col-span-2">
                <button type="submit" class="primary-button">Update Feedback</button>
                <a href="{{ route('feedback.index') }}" class="accent-button">Back to Responses</a>
            </div>
        </form>
    </div>
@endsection
