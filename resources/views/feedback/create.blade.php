@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl">
        <div class="hero-card mb-8 overflow-hidden relative">
            <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-amber-300/20 blur-3xl"></div>
            <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Laravel Project</p>
            <h1 class="mt-3 text-4xl font-bold">Feedback of Power Supply Position</h1>
            <p class="mt-4 max-w-3xl text-sm text-slate-200">
                Collect structured employee feedback about the power supply position, including workplace conditions,
                equipment support, and overall satisfaction.
            </p>
            <a href="{{ route('feedback.index') }}" class="accent-button mt-6">
                View Submitted Responses
            </a>
        </div>

        @if (session('success'))
            <div class="panel-card mb-6 border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700">
                {{ session('success') }}
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

        <form action="{{ route('feedback.store') }}" method="POST" class="panel-card grid gap-6 p-8 md:grid-cols-2">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold">Name</label>
                <input type="text" name="employee_name" value="{{ old('employee_name') }}" class="field-input" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold" data-field-label="employee_id">ID / Reference Number</label>
                <input type="text" name="employee_id" value="{{ old('employee_id') }}" class="field-input" data-field-input="employee_id">
            </div>

            <div class="md:col-span-2">
                <label class="mb-3 block text-sm font-semibold">Submission Type</label>
                <div class="grid gap-3 sm:grid-cols-2">
                    <label class="chip-option justify-center">
                        <input type="radio" name="feedback_type" value="complaint" {{ old('feedback_type', 'complaint') === 'complaint' ? 'checked' : '' }} required>
                        <span>Complaint</span>
                    </label>
                    <label class="chip-option justify-center">
                        <input type="radio" name="feedback_type" value="suggestion" {{ old('feedback_type') === 'suggestion' ? 'checked' : '' }} required>
                        <span>Suggestion</span>
                    </label>
                </div>
                <p class="mt-2 text-xs text-slate-500">Only complaints are sent to complaint tracking and technician assignment.</p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Feedback Place</label>
                <select name="feedback_place" class="field-input" data-place-select required>
                    <option value="">Select place</option>
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
                        <option value="{{ $value }}" {{ old('feedback_place') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold" data-field-label="department">Department</label>
                <input type="text" name="department" value="{{ old('department') }}" class="field-input" data-field-input="department" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold" data-field-label="shift_timing">Shift Timing</label>
                <input type="text" name="shift_timing" value="{{ old('shift_timing') }}" placeholder="Morning / Evening / Night" class="field-input" data-field-input="shift_timing" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold" data-field-label="safety_rating">Safety Rating (1-5)</label>
                <input type="number" min="1" max="5" name="safety_rating" value="{{ old('safety_rating') }}" class="field-input" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold" data-field-label="workstation_rating">Workstation Rating (1-5)</label>
                <input type="number" min="1" max="5" name="workstation_rating" value="{{ old('workstation_rating') }}" class="field-input" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold" data-field-label="equipment_rating">Equipment Rating (1-5)</label>
                <input type="number" min="1" max="5" name="equipment_rating" value="{{ old('equipment_rating') }}" class="field-input" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold" data-field-label="overall_satisfaction">Overall Satisfaction (1-5)</label>
                <input type="number" min="1" max="5" name="overall_satisfaction" value="{{ old('overall_satisfaction') }}" class="field-input" required>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold" data-field-label="strengths">What are the strengths of this position?</label>
                <textarea name="strengths" rows="4" class="field-input" data-field-input="strengths" required>{{ old('strengths') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold" data-field-label="improvements">What improvements would you suggest?</label>
                <textarea name="improvements" rows="4" class="field-input" data-field-input="improvements" required>{{ old('improvements') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold" data-field-label="additional_comments">Additional Comments</label>
                <textarea name="additional_comments" rows="3" class="field-input" data-field-input="additional_comments">{{ old('additional_comments') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="mb-3 block text-sm font-semibold" data-field-label="recommend_position">Would you recommend this position to others?</label>
                <div class="flex flex-wrap gap-4">
                    @foreach (['yes' => 'Yes', 'no' => 'No', 'maybe' => 'Maybe'] as $value => $label)
                        <label class="chip-option">
                            <input type="radio" name="recommend_position" value="{{ $value }}" {{ old('recommend_position') === $value ? 'checked' : '' }} required>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold">Feedback Date</label>
                <input type="date" name="feedback_date" value="{{ old('feedback_date', now()->toDateString()) }}" class="field-input" required>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="primary-button">
                    Submit Feedback
                </button>
            </div>
        </form>
    </div>

    <script>
        const feedbackPlaceContent = {
            home: {
                employee_id: 'House / Flat Number',
                employeeIdPlaceholder: 'Flat 204 / House 18 / Block B',
                department: 'Home Area',
                departmentPlaceholder: 'Kitchen / Living room / Bedroom',
                shift_timing: 'Time at Home',
                shiftPlaceholder: 'Morning / Afternoon / Evening / Night',
                safety_rating: 'Home Safety Rating (1-5)',
                workstation_rating: 'Comfort Rating (1-5)',
                equipment_rating: 'Appliance / Power Backup Rating (1-5)',
                overall_satisfaction: 'Overall Home Satisfaction (1-5)',
                strengths: 'What is working well at home?',
                strengthsPlaceholder: 'Mention power supply, comfort, safety, appliances, or daily convenience.',
                improvements: 'What home improvements would you suggest?',
                improvementsPlaceholder: 'Mention wiring, backup, lighting, appliance support, or safety upgrades.',
                additional_comments: 'Additional Home Comments',
                additionalPlaceholder: 'Any extra home-related feedback.',
                recommend_position: 'Would you recommend this home setup to others?',
            },
            office: {
                employee_id: 'Office / Employee ID',
                employeeIdPlaceholder: 'EMP-102 / Office floor / Desk number',
                department: 'Office Department',
                departmentPlaceholder: 'Operations / HR / IT / Accounts',
                shift_timing: 'Office Shift Timing',
                shiftPlaceholder: 'Morning / Evening / Night / General',
                safety_rating: 'Office Safety Rating (1-5)',
                workstation_rating: 'Workstation Comfort Rating (1-5)',
                equipment_rating: 'Office Equipment Rating (1-5)',
                overall_satisfaction: 'Overall Office Satisfaction (1-5)',
                strengths: 'What are the strengths of this office setup?',
                strengthsPlaceholder: 'Mention workspace, power backup, seating, lighting, or equipment.',
                improvements: 'What office improvements would you suggest?',
                improvementsPlaceholder: 'Mention maintenance, seating, electrical points, devices, or facilities.',
                additional_comments: 'Additional Office Comments',
                additionalPlaceholder: 'Any extra office-related feedback.',
                recommend_position: 'Would you recommend this office setup to others?',
            },
            bus: {
                employee_id: 'Bus Number / Route ID',
                employeeIdPlaceholder: 'Bus 45 / Route 12A',
                department: 'Bus Route / Service',
                departmentPlaceholder: 'Route number or service name',
                shift_timing: 'Travel Time',
                shiftPlaceholder: 'Morning / Afternoon / Evening / Night',
                safety_rating: 'Bus Safety Rating (1-5)',
                workstation_rating: 'Seat Comfort Rating (1-5)',
                equipment_rating: 'Bus Facility Rating (1-5)',
                overall_satisfaction: 'Overall Bus Satisfaction (1-5)',
                strengths: 'What was good about the bus service?',
                strengthsPlaceholder: 'Mention punctuality, seating, cleanliness, driving, or route coverage.',
                improvements: 'What bus service improvements would you suggest?',
                improvementsPlaceholder: 'Mention crowding, timing, cleanliness, stops, or safety.',
                additional_comments: 'Additional Bus Comments',
                additionalPlaceholder: 'Any extra bus-related feedback.',
                recommend_position: 'Would you recommend this bus service to others?',
            },
            airport: {
                employee_id: 'Flight / Booking Reference',
                employeeIdPlaceholder: 'Flight AI-202 / PNR / Terminal',
                department: 'Airport Area',
                departmentPlaceholder: 'Terminal / Check-in / Security / Gate',
                shift_timing: 'Visit Time',
                shiftPlaceholder: 'Morning / Afternoon / Evening / Night',
                safety_rating: 'Airport Safety Rating (1-5)',
                workstation_rating: 'Waiting Area Comfort Rating (1-5)',
                equipment_rating: 'Airport Facility Rating (1-5)',
                overall_satisfaction: 'Overall Airport Satisfaction (1-5)',
                strengths: 'What was good about the airport experience?',
                strengthsPlaceholder: 'Mention check-in, security, seating, cleanliness, signage, or staff support.',
                improvements: 'What airport improvements would you suggest?',
                improvementsPlaceholder: 'Mention queues, announcements, seating, washrooms, or directions.',
                additional_comments: 'Additional Airport Comments',
                additionalPlaceholder: 'Any extra airport-related feedback.',
                recommend_position: 'Would you recommend this airport experience to others?',
            },
            train_station: {
                employee_id: 'Train Number / PNR',
                employeeIdPlaceholder: 'Train 12002 / PNR / Platform number',
                department: 'Train Station Area',
                departmentPlaceholder: 'Platform / Ticket counter / Waiting room',
                shift_timing: 'Travel Time',
                shiftPlaceholder: 'Morning / Afternoon / Evening / Night',
                safety_rating: 'Station Safety Rating (1-5)',
                workstation_rating: 'Waiting Comfort Rating (1-5)',
                equipment_rating: 'Station Facility Rating (1-5)',
                overall_satisfaction: 'Overall Station Satisfaction (1-5)',
                strengths: 'What was good about the train station?',
                strengthsPlaceholder: 'Mention platform access, cleanliness, announcements, seating, or ticket service.',
                improvements: 'What train station improvements would you suggest?',
                improvementsPlaceholder: 'Mention crowd control, cleanliness, signage, lighting, or facilities.',
                additional_comments: 'Additional Train Station Comments',
                additionalPlaceholder: 'Any extra train station-related feedback.',
                recommend_position: 'Would you recommend this train station experience to others?',
            },
            school: {
                employee_id: 'Student / Staff ID',
                employeeIdPlaceholder: 'Student ID / Staff ID / Class roll number',
                department: 'School Section',
                departmentPlaceholder: 'Classroom / Lab / Library / Playground',
                shift_timing: 'School Timing',
                shiftPlaceholder: 'Morning / Afternoon / Full day',
                safety_rating: 'School Safety Rating (1-5)',
                workstation_rating: 'Classroom Comfort Rating (1-5)',
                equipment_rating: 'Learning Facility Rating (1-5)',
                overall_satisfaction: 'Overall School Satisfaction (1-5)',
                strengths: 'What is working well at school?',
                strengthsPlaceholder: 'Mention classrooms, teachers, labs, safety, cleanliness, or facilities.',
                improvements: 'What school improvements would you suggest?',
                improvementsPlaceholder: 'Mention seating, labs, washrooms, playground, safety, or power supply.',
                additional_comments: 'Additional School Comments',
                additionalPlaceholder: 'Any extra school-related feedback.',
                recommend_position: 'Would you recommend this school environment to others?',
            },
            hospital: {
                employee_id: 'Patient / Appointment ID',
                employeeIdPlaceholder: 'Patient ID / Token number / Appointment ID',
                department: 'Hospital Area',
                departmentPlaceholder: 'OPD / Ward / Reception / Pharmacy',
                shift_timing: 'Visit Time',
                shiftPlaceholder: 'Morning / Afternoon / Evening / Night',
                safety_rating: 'Hospital Safety Rating (1-5)',
                workstation_rating: 'Waiting Comfort Rating (1-5)',
                equipment_rating: 'Medical Facility Rating (1-5)',
                overall_satisfaction: 'Overall Hospital Satisfaction (1-5)',
                strengths: 'What was good about the hospital service?',
                strengthsPlaceholder: 'Mention staff, cleanliness, waiting area, guidance, or medical facilities.',
                improvements: 'What hospital improvements would you suggest?',
                improvementsPlaceholder: 'Mention waiting time, seating, directions, cleanliness, or staff support.',
                additional_comments: 'Additional Hospital Comments',
                additionalPlaceholder: 'Any extra hospital-related feedback.',
                recommend_position: 'Would you recommend this hospital service to others?',
            },
            market: {
                employee_id: 'Shop / Stall Number',
                employeeIdPlaceholder: 'Shop 12 / Stall B4 / Parking token',
                department: 'Market Area',
                departmentPlaceholder: 'Shop / Food area / Parking / Walkway',
                shift_timing: 'Visit Time',
                shiftPlaceholder: 'Morning / Afternoon / Evening / Night',
                safety_rating: 'Market Safety Rating (1-5)',
                workstation_rating: 'Shopping Comfort Rating (1-5)',
                equipment_rating: 'Market Facility Rating (1-5)',
                overall_satisfaction: 'Overall Market Satisfaction (1-5)',
                strengths: 'What was good about the market?',
                strengthsPlaceholder: 'Mention shops, cleanliness, lighting, parking, crowd management, or service.',
                improvements: 'What market improvements would you suggest?',
                improvementsPlaceholder: 'Mention crowding, waste, lighting, parking, safety, or pathways.',
                additional_comments: 'Additional Market Comments',
                additionalPlaceholder: 'Any extra market-related feedback.',
                recommend_position: 'Would you recommend this market to others?',
            },
            public_place: {
                employee_id: 'Location / Service Reference',
                employeeIdPlaceholder: 'Gate number / Area name / Service counter',
                department: 'Public Place Area',
                departmentPlaceholder: 'Park / Road / Community hall / Public office',
                shift_timing: 'Visit Time',
                shiftPlaceholder: 'Morning / Afternoon / Evening / Night',
                safety_rating: 'Public Safety Rating (1-5)',
                workstation_rating: 'Public Comfort Rating (1-5)',
                equipment_rating: 'Public Facility Rating (1-5)',
                overall_satisfaction: 'Overall Public Place Satisfaction (1-5)',
                strengths: 'What was good about this public place?',
                strengthsPlaceholder: 'Mention cleanliness, safety, access, lighting, seating, or public services.',
                improvements: 'What public place improvements would you suggest?',
                improvementsPlaceholder: 'Mention safety, cleanliness, lighting, accessibility, or maintenance.',
                additional_comments: 'Additional Public Place Comments',
                additionalPlaceholder: 'Any extra public-place feedback.',
                recommend_position: 'Would you recommend this public place to others?',
            },
            other: {
                employee_id: 'ID / Reference Number',
                employeeIdPlaceholder: 'Enter any related ID or reference number',
                department: 'Feedback Area',
                departmentPlaceholder: 'Enter the area or category',
                shift_timing: 'Visit / Usage Time',
                shiftPlaceholder: 'Morning / Afternoon / Evening / Night',
                safety_rating: 'Safety Rating (1-5)',
                workstation_rating: 'Comfort Rating (1-5)',
                equipment_rating: 'Facility Rating (1-5)',
                overall_satisfaction: 'Overall Satisfaction (1-5)',
                strengths: 'What are the strengths?',
                strengthsPlaceholder: 'Mention the best parts of this experience.',
                improvements: 'What improvements would you suggest?',
                improvementsPlaceholder: 'Mention what should be improved.',
                additional_comments: 'Additional Comments',
                additionalPlaceholder: 'Any extra feedback.',
                recommend_position: 'Would you recommend this experience to others?',
            },
        };

        const defaultFeedbackContent = {
            employee_id: 'ID / Reference Number',
            employeeIdPlaceholder: 'Enter ID only if needed',
            department: 'Department',
            departmentPlaceholder: '',
            shift_timing: 'Shift Timing',
            shiftPlaceholder: 'Morning / Evening / Night',
            safety_rating: 'Safety Rating (1-5)',
            workstation_rating: 'Workstation Rating (1-5)',
            equipment_rating: 'Equipment Rating (1-5)',
            overall_satisfaction: 'Overall Satisfaction (1-5)',
            strengths: 'What are the strengths of this position?',
            strengthsPlaceholder: '',
            improvements: 'What improvements would you suggest?',
            improvementsPlaceholder: '',
            additional_comments: 'Additional Comments',
            additionalPlaceholder: '',
            recommend_position: 'Would you recommend this position to others?',
        };

        const placeSelect = document.querySelector('[data-place-select]');

        const updateFeedbackForm = () => {
            const content = feedbackPlaceContent[placeSelect.value] || defaultFeedbackContent;

            Object.entries(content).forEach(([key, value]) => {
                const label = document.querySelector(`[data-field-label="${key}"]`);

                if (label && !key.endsWith('Placeholder')) {
                    label.textContent = value;
                }
            });

            document.querySelector('[data-field-input="department"]').placeholder = content.departmentPlaceholder;
            document.querySelector('[data-field-input="employee_id"]').placeholder = content.employeeIdPlaceholder;
            document.querySelector('[data-field-input="shift_timing"]').placeholder = content.shiftPlaceholder;
            document.querySelector('[data-field-input="strengths"]').placeholder = content.strengthsPlaceholder;
            document.querySelector('[data-field-input="improvements"]').placeholder = content.improvementsPlaceholder;
            document.querySelector('[data-field-input="additional_comments"]').placeholder = content.additionalPlaceholder;
        };

        placeSelect.addEventListener('change', updateFeedbackForm);
        updateFeedbackForm();
    </script>
@endsection
