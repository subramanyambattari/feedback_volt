@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl">
        <div class="hero-card mb-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-amber-300">AI Assistant</p>
                    <h1 class="mt-3 text-4xl font-bold">Power Help Desk</h1>
                    <p class="mt-4 max-w-3xl text-sm text-slate-200">
                        Ask about power fluctuation, transformer failure reporting, outage handling, and safety suggestions.
                    </p>
                </div>
                <span class="w-fit rounded-full bg-cyan-300 px-4 py-2 text-sm font-extrabold text-slate-950">AJAX Chat</span>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">
            <aside class="panel-card p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-amber-300">Suggested Questions</p>
                <h2 class="mt-2 text-2xl font-bold">Quick Start</h2>

                <div class="mt-6 grid gap-3">
                    @foreach ($suggestedQuestions as $question)
                        <button type="button" class="rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4 text-left text-sm font-semibold text-cyan-50 transition hover:-translate-y-1 hover:border-amber-200/70 hover:bg-slate-900" data-question="{{ $question }}">
                            {{ $question }}
                        </button>
                    @endforeach
                </div>
            </aside>

            <section class="panel-card grid min-h-[34rem] grid-rows-[1fr_auto] p-6">
                <div class="space-y-4 overflow-y-auto pr-2" data-chat-log>
                    <div class="max-w-[85%] rounded-3xl border border-cyan-100/25 bg-slate-900/80 p-4">
                        <p class="text-xs uppercase tracking-[0.18em] text-amber-300">Assistant</p>
                        <p class="mt-2 text-sm text-slate-200">
                            Hello. Ask a power supply question and I will suggest the safest next step.
                        </p>
                    </div>
                </div>

                <form class="mt-6 flex flex-col gap-3 sm:flex-row" data-assistant-form>
                    @csrf
                    <input type="text" name="message" class="field-input" placeholder="Type your question..." autocomplete="off" required>
                    <button type="submit" class="primary-button sm:min-w-32">Ask</button>
                </form>
            </section>
        </div>
    </div>

    <script>
        const assistantForm = document.querySelector('[data-assistant-form]');
        const chatLog = document.querySelector('[data-chat-log]');
        const questionButtons = document.querySelectorAll('[data-question]');

        const appendMessage = (label, message, align = 'left') => {
            const bubble = document.createElement('div');
            bubble.className = `max-w-[85%] rounded-3xl border p-4 ${align === 'right' ? 'ml-auto border-amber-200/40 bg-amber-300 text-slate-950' : 'border-cyan-100/25 bg-slate-900/80 text-slate-200'}`;

            const meta = document.createElement('p');
            meta.className = `text-xs uppercase tracking-[0.18em] ${align === 'right' ? 'text-slate-700' : 'text-amber-300'}`;
            meta.textContent = label;

            const body = document.createElement('p');
            body.className = 'mt-2 text-sm';
            body.textContent = message;

            bubble.append(meta, body);
            chatLog.appendChild(bubble);
            chatLog.scrollTop = chatLog.scrollHeight;

            return bubble;
        };

        const askAssistant = async (message) => {
            appendMessage('You', message, 'right');
            const loading = appendMessage('Assistant', 'Thinking...');

            const response = await fetch('{{ route('assistant.ask') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': assistantForm.querySelector('[name="_token"]').value,
                },
                body: JSON.stringify({ message }),
            });

            const data = await response.json();
            loading.querySelector('p:last-child').textContent = data.reply || 'I could not prepare a reply right now.';
        };

        assistantForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const input = assistantForm.querySelector('[name="message"]');
            const message = input.value.trim();

            if (! message) {
                return;
            }

            input.value = '';
            await askAssistant(message);
        });

        questionButtons.forEach((button) => {
            button.addEventListener('click', () => {
                askAssistant(button.dataset.question);
            });
        });
    </script>
@endsection
