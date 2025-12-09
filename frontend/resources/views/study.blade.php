{{-- 
    Study view housing generation controls, queue viewer, and recommendation output.
    Inputs: $jobId string|null active job identifier; $state array<string, mixed> current job state; $tunableParameters array<int, array<string, string|int>> field metadata; $queue array<string, mixed> queued history entries.
    Outputs: HTML page enabling users to run the generator, inspect the pending queue, and review returned plans.
--}} 
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Powered Learning') }} | Study</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-[#e3f7f0] via-[#f5f1e8] to-[#dff0ff] text-slate-900 antialiased">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -left-20 top-10 h-64 w-64 rounded-full bg-[#2d8f6f] blur-3xl opacity-20"></div>
            <div class="absolute -right-16 bottom-12 h-72 w-72 rounded-full bg-[#ffb347] blur-3xl opacity-25"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_25%_15%,rgba(45,143,111,0.07),transparent_32%),radial-gradient(circle_at_80%_20%,rgba(255,179,71,0.08),transparent_28%)]"></div>
        </div>
        <main class="relative mx-auto flex min-h-screen max-w-6xl flex-col gap-10 px-6 py-10">
            <header class="flex items-center justify-between gap-4 rounded-2xl bg-white/85 px-6 py-4 shadow-lg shadow-slate-200/60 ring-1 ring-slate-100 backdrop-blur">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Study run</p>
                    <h1 class="text-3xl font-semibold leading-tight text-slate-900">Personalised session generator</h1>
                </div>
                <div class="flex gap-3">
                    <a
                        href="{{ route('dashboard') }}"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:-translate-y-[1px] hover:shadow-md hover:shadow-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]"
                    >
                        Back to dashboard
                    </a>
                    <a
                        href="{{ route('logout') }}"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:-translate-y-[1px] hover:shadow-md hover:shadow-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]"
                    >
                        Logout
                    </a>
                </div>
            </header>

            <section class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="space-y-6">
                    <div class="relative overflow-hidden rounded-3xl bg-white/90 p-8 shadow-xl shadow-slate-200/70 ring-1 ring-slate-100 backdrop-blur">
                        <div class="absolute -left-16 -top-16 h-48 w-48 rounded-full bg-[#2d8f6f] opacity-10 blur-3xl"></div>
                        <div class="absolute -right-10 bottom-0 h-40 w-40 rounded-full bg-[#ffb347] opacity-15 blur-3xl"></div>
                        <div class="relative space-y-6">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Generation</p>
                                    <h2 class="text-2xl font-semibold text-slate-900">Tune your study run</h2>
                                    <p class="text-sm leading-relaxed text-slate-700">Adjust timing and cadence, then generate a personalised queue without leaving this page.</p>
                                </div>
                                <div id="status-chip" class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700 ring-1 ring-slate-200">
                                    Idle
                                </div>
                            </div>
                            <form id="generate-form" method="POST" action="{{ route('study.start') }}" class="space-y-5">
                                @csrf
                                <div class="grid gap-4 md:grid-cols-2">
                                    @foreach($tunableParameters as $parameter)
                                        <label class="flex flex-col gap-1 text-sm font-semibold text-slate-800">
                                            <span class="flex items-center justify-between text-xs uppercase tracking-wide text-slate-600">
                                                <span>{{ $parameter['label'] }}</span>
                                                <span class="rounded-full bg-white px-2 py-0.5 text-[10px] font-semibold text-[#2d8f6f]">{{ $parameter['key'] }}</span>
                                            </span>
                                            <input
                                                type="{{ $parameter['type'] }}"
                                                name="{{ $parameter['key'] }}"
                                                value="{{ old($parameter['key'], $parameter['value']) }}"
                                                min="1"
                                                step="1"
                                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm transition focus:border-[#2d8f6f] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]/40"
                                            />
                                            @error($parameter['key'])
                                                <span class="text-xs font-normal text-red-700">{{ $message }}</span>
                                            @else
                                                <span class="text-xs font-normal text-slate-600">{{ $parameter['helper'] }}</span>
                                            @enderror
                                        </label>
                                    @endforeach
                                </div>
                                <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-100 bg-slate-50/80 px-4 py-3">
                                    <div class="space-y-1">
                                        <p id="status-text" class="text-sm text-slate-700">Press generate to start a personalised study run.</p>
                                        <p class="text-xs font-semibold text-slate-600">Job ID: <span id="job-id" class="font-mono text-xs">{{ $jobId ?? 'Not started' }}</span></p>
                                    </div>
                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-2 rounded-xl bg-[#2d8f6f] px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-[#2d8f6f]/30 transition hover:-translate-y-[1px] hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2d8f6f]"
                                    >
                                        Generate
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="relative overflow-hidden rounded-3xl bg-white/90 p-8 shadow-xl shadow-slate-200/70 ring-1 ring-slate-100 backdrop-blur">
                        <div class="absolute -right-10 -top-16 h-48 w-48 rounded-full bg-[#ffb347] opacity-10 blur-3xl"></div>
                        <div class="absolute -left-14 bottom-0 h-40 w-40 rounded-full bg-[#2d8f6f] opacity-10 blur-3xl"></div>
                        <div class="relative space-y-5">
                            <div class="space-y-1">
                                <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Queue</p>
                                <h2 class="text-2xl font-semibold text-slate-900">Next queued subject</h2>
                                <p class="text-sm leading-relaxed text-slate-700">Subjects are ordered by the earliest logged entries with a studied date of 1900. When the queue is empty, generate more.</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-[#2d8f6f]/5 p-4 shadow-inner shadow-[#2d8f6f]/10 ring-1 ring-[#2d8f6f]/20">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="space-y-2">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-[#2d8f6f]">Next up</p>
                                        <h3 id="current-subject" class="text-xl font-semibold text-slate-900">No queued subjects</h3>
                                        <p id="subject-meta" class="text-sm leading-relaxed text-slate-700">Generate a plan to queue more study sessions.</p>
                                        <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-700">
                                            <span id="subject-position" class="rounded-full bg-white px-3 py-1 ring-1 ring-slate-200">0 / 0</span>
                                            <span id="queue-count" class="rounded-full bg-white px-3 py-1 ring-1 ring-slate-200">0 queued subjects</span>
                                        </div>
                                    </div>
                                    <button
                                        id="next-subject"
                                        class="inline-flex items-center justify-center rounded-xl bg-[#2d8f6f] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-[1px] hover:shadow-md hover:shadow-[#2d8f6f]/30 focus:outline-none focus:ring-2 focus:ring-[#2d8f6f] disabled:cursor-not-allowed disabled:opacity-60 disabled:shadow-none"
                                        disabled
                                    >
                                        Start subject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-3xl bg-white/90 p-8 shadow-xl shadow-slate-200/70 ring-1 ring-slate-100 backdrop-blur">
                    <div class="absolute -left-14 -top-12 h-44 w-44 rounded-full bg-[#2d8f6f] opacity-10 blur-3xl"></div>
                    <div class="absolute -right-10 bottom-0 h-48 w-48 rounded-full bg-[#ffb347] opacity-10 blur-3xl"></div>
                    <div class="relative space-y-5">
                        <div class="space-y-1">
                            <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Output</p>
                            <h2 class="text-2xl font-semibold text-slate-900">Recommended schedule</h2>
                            <p class="text-sm leading-relaxed text-slate-700">Your personalised plan will appear here once the recommender finishes. Feel free to copy the details for your study session.</p>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between gap-3 rounded-2xl border border-slate-100 bg-slate-50/80 px-4 py-3">
                                <p class="text-sm text-slate-700">Shots returned</p>
                                <span id="shot-count" class="rounded-full bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-slate-600 ring-1 ring-slate-200">Awaiting plan</span>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Parsed plan</p>
                                </div>
                                <div id="plan-list" class="grid gap-3 lg:grid-cols-2"></div>
                            </div>
                            <div class="space-y-2">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Raw output</p>
                                <pre id="result-block" class="mt-2 max-h-[260px] overflow-auto rounded-2xl bg-slate-900 px-4 py-3 text-xs font-semibold leading-6 text-slate-100 shadow-inner shadow-slate-800/50 ring-1 ring-slate-800/60">
Waiting for results...
                                </pre>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <script>
            const statusUrl = "{{ route('study.status') }}";
            const queueUrl = "{{ route('study.queue') }}";
            const touchHistoryUrl = "{{ route('study.history.touch') }}";
            const initialState = @json($state);
            const initialQueue = @json($queue);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const statusChip = document.getElementById('status-chip');
            const statusText = document.getElementById('status-text');
            const resultBlock = document.getElementById('result-block');
            const planList = document.getElementById('plan-list');
            const currentSubject = document.getElementById('current-subject');
            const subjectMeta = document.getElementById('subject-meta');
            const subjectPosition = document.getElementById('subject-position');
            const nextSubjectButton = document.getElementById('next-subject');
            const shotCount = document.getElementById('shot-count');
            const queueCount = document.getElementById('queue-count');
            const jobIdBadge = document.getElementById('job-id');
            const generateForm = document.getElementById('generate-form');

            let subjectQueue = Array.isArray(initialQueue?.entries) ? initialQueue.entries : [];
            let jobStatus = initialState.status ?? 'idle';

            function renderState(state) {
                const status = state.status ?? 'idle';
                jobStatus = status;
                if (status === 'done') {
                    statusChip.textContent = 'Complete';
                    statusChip.className = 'inline-flex items-center gap-2 rounded-full bg-[#e7f7ef] px-3 py-1 text-sm font-semibold text-[#2d8f6f] ring-1 ring-[#2d8f6f]/30';
                    statusText.textContent = 'Your personalised plan is ready.';
                    const parsed = parseRecommenderResult(state.result ?? {});
                    renderPlan(parsed.shots, parsed.rawText);
                    resultBlock.textContent = parsed.rawText;
                    if (shotCount) {
                        shotCount.textContent = parsed.shots?.length ? `${parsed.shots.length} shot${parsed.shots.length === 1 ? '' : 's'}` : 'Plan ready';
                    }
                    refreshQueue();
                } else if (status === 'error') {
                    statusChip.textContent = 'Error';
                    statusChip.className = 'inline-flex items-center gap-2 rounded-full bg-[#fee2e2] px-3 py-1 text-sm font-semibold text-[#991b1b] ring-1 ring-[#fca5a5]';
                    statusText.textContent = state.error ?? 'The generator reported a problem.';
                    resultBlock.textContent = state.error ?? 'The generator reported a problem.';
                    resetPlanDisplay();
                } else if (status === 'pending') {
                    statusChip.textContent = 'Pending';
                    statusChip.className = 'inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700 ring-1 ring-slate-200';
                    statusText.textContent = 'Preparing to run your plan. Please keep this tab open.';
                    resultBlock.textContent = 'Waiting for results...';
                    resetPlanDisplay();
                } else {
                    statusChip.textContent = 'Idle';
                    statusChip.className = 'inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700 ring-1 ring-slate-200';
                    statusText.textContent = state.message ?? 'Press generate to start a personalised study run.';
                    resultBlock.textContent = 'Waiting for results...';
                    resetPlanDisplay();
                }

                if (state.jobId && jobIdBadge) {
                    jobIdBadge.textContent = state.jobId;
                } else if (jobIdBadge && !state.jobId) {
                    jobIdBadge.textContent = 'Not started';
                }

                if (state.queue) {
                    renderQueue(state.queue);
                }
            }

            function resetPlanDisplay() {
                planList.innerHTML = '<p class="text-sm text-slate-700">Waiting for the recommender to return a plan.</p>';
                if (shotCount) {
                    shotCount.textContent = 'Awaiting plan';
                }
            }

            function parseRecommenderResult(result) {
                if (result && typeof result === 'object' && Array.isArray(result.shots)) {
                    const rawText = typeof result.message === 'string'
                        ? result.message
                        : JSON.stringify(result, null, 2);
                    return {
                        shots: result.shots,
                        insights: result.insights ?? {},
                        rawText,
                    };
                }

                let rawText = '';
                if (typeof result === 'string') {
                    rawText = result;
                } else if (result && typeof result === 'object') {
                    rawText = typeof result.message === 'string'
                        ? result.message
                        : JSON.stringify(result, null, 2);
                } else {
                    rawText = String(result ?? '');
                }

                const parsedMessage = parseMessage(rawText);
                return { ...parsedMessage, rawText };
            }

            function parseMessage(message) {
                const lines = message.split(/\r?\n/).map(line => line.trim());
                const shots = [];
                let index = 0;

                while (index < lines.length) {
                    const numberedShot = lines[index].match(/^Study session plan \(shot\s*(\d+)\):/i);
                    const singleShot = numberedShot ? null : lines[index].match(/^Study session plan:/i);

                    if (!numberedShot && !singleShot) {
                        break;
                    }

                    const shotNumber = numberedShot ? (Number(numberedShot[1]) || shots.length + 1) : shots.length + 1;
                    index += 1;

                    const subjects = [];
                    while (index < lines.length && /^\d+\.\s*/.test(lines[index])) {
                        const line = lines[index].replace(/^\d+\.\s*/, '');
                        const idMatch = line.match(/\(id:\s*([^)]+)\)\s*$/i);
                        const historyEntryId = idMatch ? idMatch[1].trim() : null;
                        const subjectName = idMatch ? line.replace(/\(id:\s*([^)]+)\)\s*$/i, '').trim() : line.trim();
                        subjects.push({ subject: subjectName, historyEntryId });
                        index += 1;
                    }

                    const historyEntryIds = subjects.map(item => item.historyEntryId).filter(Boolean);
                    shots.push({ shot: shotNumber, subjects, historyEntryIds });

                    while (index < lines.length && lines[index] === '') {
                        index += 1;
                    }
                }

                const insightsLineIndex = lines.findIndex(line => /^Overall session insights:/i.test(line));
                const insights = {};

                if (insightsLineIndex !== -1) {
                    for (let i = insightsLineIndex + 1; i < lines.length; i += 1) {
                        const line = lines[i];
                        if (!line) {
                            continue;
                        }

                        if (/^-\s+/u.test(line)) {
                            const trimmed = line.replace(/^-\s+/, '');
                            const separatorIndex = trimmed.indexOf(':');
                            if (separatorIndex !== -1) {
                                const key = trimmed.slice(0, separatorIndex).trim().toLowerCase();
                                const value = trimmed.slice(separatorIndex + 1).trim();
                                insights[key] = value;
                            }
                        } else if (line.toLowerCase().startsWith('subject frequency:')) {
                            const frequencies = {};
                            const remainder = line.split(':').slice(1).join(':').trim();
                            remainder.split(',').forEach(pair => {
                                const lastColon = pair.lastIndexOf(':');
                                if (lastColon !== -1) {
                                    const subject = pair.slice(0, lastColon).trim();
                                    const count = pair.slice(lastColon + 1).trim();
                                    frequencies[subject] = Number.isNaN(Number(count)) ? count : Number(count);
                                }
                            });
                            insights.frequency = frequencies;
                        }
                    }
                }

                return { shots, insights };
            }

            function renderPlan(shots, rawText) {
                if (!planList) {
                    resultBlock.textContent = rawText || 'Plan container missing in the page.';
                    return;
                }

                if (!Array.isArray(shots) || shots.length === 0) {
                    planList.innerHTML = '<p class="text-sm text-slate-700">Unable to parse a plan from the generator. Raw output is shown below.</p>';
                    resultBlock.textContent = rawText || 'No output received.';
                    if (shotCount) {
                        shotCount.textContent = 'Plan unavailable';
                    }
                    return;
                }

                planList.innerHTML = shots.map((shot, shotIndex) => {
                    const shotNumber = shot?.shot ?? shotIndex + 1;
                    const subjects = (shot.subjects || []).map((subject, idx) => {
                        const subjectLabel = typeof subject === 'string' ? subject : (subject?.subject ?? '');
                        return `
                        <li class="flex items-start gap-2">
                            <span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">#${idx + 1}</span>
                            <span class="text-sm font-semibold text-slate-800">${subjectLabel || 'Unnamed subject'}</span>
                        </li>
                        `;
                    }).join('');

                    return `
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4 shadow-sm ring-1 ring-slate-100/60">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wide text-[#2d8f6f]">Shot ${shotNumber}</span>
                            </div>
                            <ol class="space-y-1">${subjects}</ol>
                        </div>
                    `;
                }).join('');

                resultBlock.textContent = rawText || 'No output received.';
            }

            async function pollStatus() {
                try {
                    const response = await fetch(statusUrl);
                    const data = await response.json();
                    renderState(data);
                    if (data.status === 'pending') {
                        setTimeout(pollStatus, 1200);
                    }
                } catch (error) {
                    statusChip.textContent = 'Error';
                    statusChip.className = 'inline-flex items-center gap-2 rounded-full bg-[#fee2e2] px-3 py-1 text-sm font-semibold text-[#991b1b] ring-1 ring-[#fca5a5]';
                    statusText.textContent = 'Unable to contact the generator. Please refresh to try again.';
                    resultBlock.textContent = String(error);
                }
            }

            async function refreshQueue() {
                try {
                    const response = await fetch(queueUrl);
                    const data = await response.json();
                    renderQueue(data);
                } catch (error) {
                    console.error('Unable to refresh the queue', error);
                }
            }

            async function touchHistoryEntry(historyEntryId, subjectLabel) {
                if (!csrfToken || !touchHistoryUrl || !historyEntryId) {
                    return false;
                }

                try {
                    const response = await fetch(touchHistoryUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            historyEntryId: historyEntryId ?? null,
                            subject: subjectLabel ?? null,
                            occurrence: 1,
                        }),
                    });

                    const payload = await response.json();
                    return payload.status === 'ok';
                } catch (error) {
                    console.error('Unable to update history entry date', error);
                    return false;
                }
            }

            async function completeCurrentSubject() {
                if (!subjectQueue.length) {
                    triggerGeneration();
                    return;
                }

                const current = subjectQueue[0];
                nextSubjectButton.disabled = true;
                const previousLabel = nextSubjectButton.textContent;
                nextSubjectButton.textContent = 'Marking...';

                const updated = await touchHistoryEntry(current.historyEntryId ?? null, current.subject ?? null);
                if (!updated) {
                    nextSubjectButton.textContent = previousLabel;
                    nextSubjectButton.disabled = false;
                    return;
                }

                await refreshQueue();
                nextSubjectButton.textContent = subjectQueue.length > 1 ? 'Next subject' : 'Start subject';
            }

            function triggerGeneration() {
                if (!generateForm) {
                    return;
                }
                statusText.textContent = 'Generating your next study plan...';
                statusChip.textContent = 'Pending';
                statusChip.className = 'inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700 ring-1 ring-slate-200';
                if (typeof generateForm.requestSubmit === 'function') {
                    generateForm.requestSubmit();
                } else {
                    generateForm.submit();
                }
            }

            function renderQueue(queueData) {
                const entries = Array.isArray(queueData?.entries) ? queueData.entries : [];
                subjectQueue = entries;
                const count = queueData?.count ?? entries.length;

                if (queueCount) {
                    queueCount.textContent = count === 1 ? '1 queued subject' : `${count} queued subjects`;
                }

                if (!entries.length) {
                    currentSubject.textContent = 'No queued subjects';
                    subjectMeta.textContent = 'Generate a plan to queue more study sessions.';
                    subjectPosition.textContent = '0 / 0';
                    nextSubjectButton.textContent = jobStatus === 'pending' ? 'Generating...' : 'Generate subjects';
                    nextSubjectButton.disabled = jobStatus === 'pending';
                    return;
                }

                const next = entries[0];
                currentSubject.textContent = next.subject || 'Queued subject';
                subjectMeta.textContent = next.logged_at ? `Queued at ${next.logged_at}` : 'Queued subject ready to start.';
                subjectPosition.textContent = `1 / ${entries.length}`;
                nextSubjectButton.textContent = entries.length > 1 ? 'Next subject' : 'Complete queue';
                nextSubjectButton.disabled = false;
            }

            nextSubjectButton.addEventListener('click', completeCurrentSubject);

            renderState(initialState);
            renderQueue(initialQueue);
            if ((initialState.status ?? 'idle') === 'pending') {
                pollStatus();
            }
        </script>
    </body>
</html>
