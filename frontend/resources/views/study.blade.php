{{--
    Study view showing queued recommendation status and results.
    Inputs: $jobId string unique identifier for the queued run; $state array containing the current status, result, or error.
    Outputs: HTML page that redirects users from the dashboard and polls job progress before displaying the generated study plan.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <main class="relative mx-auto flex min-h-screen max-w-5xl flex-col gap-10 px-6 py-10">
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

            <section class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
                <div class="space-y-6">
                    <div class="relative overflow-hidden rounded-3xl bg-white/90 p-8 shadow-xl shadow-slate-200/70 ring-1 ring-slate-100 backdrop-blur">
                        <div class="absolute -left-16 -top-16 h-48 w-48 rounded-full bg-[#2d8f6f] opacity-10 blur-3xl"></div>
                        <div class="absolute -right-10 bottom-0 h-40 w-40 rounded-full bg-[#ffb347] opacity-15 blur-3xl"></div>
                        <div class="relative space-y-5">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Status</p>
                                    <h2 class="text-2xl font-semibold text-slate-900">Generating your study path</h2>
                                    <p class="text-sm leading-relaxed text-slate-700">We are running your tuning choices through the recommender. This page will update automatically.</p>
                                </div>
                                <div id="status-chip" class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700 ring-1 ring-slate-200">
                                    Pending
                                </div>
                            </div>
                            <div class="grid gap-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-4 text-sm text-slate-800">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-semibold text-slate-600">Job ID</span>
                                    <span class="rounded-lg bg-white px-3 py-1 font-mono text-xs font-semibold text-slate-800 ring-1 ring-slate-200">{{ $jobId }}</span>
                                </div>
                                <p id="status-text" class="text-slate-700">Preparing to run your plan. Please keep this tab open.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-3xl bg-white/90 p-8 shadow-xl shadow-slate-200/70 ring-1 ring-slate-100 backdrop-blur">
                    <div class="absolute -right-10 -top-16 h-48 w-48 rounded-full bg-[#ffb347] opacity-10 blur-3xl"></div>
                    <div class="absolute -left-14 bottom-0 h-40 w-40 rounded-full bg-[#2d8f6f] opacity-10 blur-3xl"></div>
                    <div class="relative space-y-5">
                        <div class="space-y-1">
                            <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Output</p>
                            <h2 class="text-2xl font-semibold text-slate-900">Recommended schedule</h2>
                            <p class="text-sm leading-relaxed text-slate-700">Your personalised plan will appear here once the recommender finishes. Feel free to copy the details for your study session.</p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Shots</p>
                                <div id="plan-list" class="mt-2 grid gap-3 lg:grid-cols-2"></div>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 shadow-inner shadow-slate-200/50">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Insights</p>
                                <div id="insights-block" class="mt-3 grid gap-2 text-sm text-slate-800"></div>
                            </div>
                            <div>
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
            const initialState = @json($state);
            const statusChip = document.getElementById('status-chip');
            const statusText = document.getElementById('status-text');
            const resultBlock = document.getElementById('result-block');
            const planList = document.getElementById('plan-list');
            const insightsBlock = document.getElementById('insights-block');

            function renderState(state) {
                const status = state.status ?? 'pending';
                if (status === 'done') {
                    statusChip.textContent = 'Complete';
                    statusChip.className = 'inline-flex items-center gap-2 rounded-full bg-[#e7f7ef] px-3 py-1 text-sm font-semibold text-[#2d8f6f] ring-1 ring-[#2d8f6f]/30';
                    statusText.textContent = 'Your personalised plan is ready.';
                    const parsed = parseRecommenderResult(state.result ?? {});
                    renderPlan(parsed.shots, parsed.rawText);
                    renderInsights(parsed.insights);
                    resultBlock.textContent = parsed.rawText;
                } else if (status === 'error') {
                    statusChip.textContent = 'Error';
                    statusChip.className = 'inline-flex items-center gap-2 rounded-full bg-[#fee2e2] px-3 py-1 text-sm font-semibold text-[#991b1b] ring-1 ring-[#fca5a5]';
                    statusText.textContent = state.error ?? 'The generator reported a problem.';
                    resultBlock.textContent = state.error ?? 'The generator reported a problem.';
                } else {
                    statusChip.textContent = 'Pending';
                    statusChip.className = 'inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700 ring-1 ring-slate-200';
                    statusText.textContent = 'Preparing to run your plan. Please keep this tab open.';
                    resultBlock.textContent = 'Waiting for results...';
                }
            }

            function parseRecommenderResult(result) {
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
                    const match = lines[index].match(/^Study session plan \\(shot\\s*(\\d+)\\):/i);
                    if (!match) {
                        break;
                    }

                    const shotNumber = Number(match[1]) || shots.length + 1;
                    index += 1;

                    const subjects = [];
                    while (index < lines.length && /^\\d+\\.\\s*/.test(lines[index])) {
                        subjects.push(lines[index].replace(/^\\d+\\.\\s*/, ''));
                        index += 1;
                    }

                    shots.push({ shot: shotNumber, subjects });

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

                        if (/^-\\s+/u.test(line)) {
                            const trimmed = line.replace(/^-\\s+/, '');
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
                if (!Array.isArray(shots) || shots.length === 0) {
                    planList.innerHTML = '<p class="text-sm text-slate-700">Unable to parse a plan from the generator. Raw output is shown below.</p>';
                    resultBlock.textContent = rawText || 'No output received.';
                    return;
                }

                planList.innerHTML = shots.map(shot => {
                    const subjects = (shot.subjects || []).map((subject, idx) => `
                        <li class="flex items-start gap-2">
                            <span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">#${idx + 1}</span>
                            <span class="text-sm font-semibold text-slate-800">${subject}</span>
                        </li>
                    `).join('');

                    return `
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4 shadow-sm ring-1 ring-slate-100/60">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wide text-[#2d8f6f]">Shot ${shot.shot}</span>
                            </div>
                            <ol class="space-y-1">${subjects}</ol>
                        </div>
                    `;
                }).join('');

                resultBlock.textContent = rawText || 'No output received.';
            }

            function renderInsights(insights) {
                if (!insights || Object.keys(insights).length === 0) {
                    insightsBlock.innerHTML = '<p class="text-sm text-slate-700">No additional insights provided.</p>';
                    return;
                }

                const summaryItems = [];
                if (insights['shots executed']) {
                    summaryItems.push(`<div class="flex items-center justify-between"><span class="text-slate-600">Shots executed</span><span class="font-semibold text-slate-900">${insights['shots executed']}</span></div>`);
                }
                if (insights['total sessions scheduled']) {
                    summaryItems.push(`<div class="flex items-center justify-between"><span class="text-slate-600">Total sessions scheduled</span><span class="font-semibold text-slate-900">${insights['total sessions scheduled']}</span></div>`);
                }
                if (insights['unique subjects scheduled']) {
                    summaryItems.push(`<div class="flex items-center justify-between"><span class="text-slate-600">Unique subjects scheduled</span><span class="font-semibold text-slate-900">${insights['unique subjects scheduled']}</span></div>`);
                }

                const frequency = insights.frequency && typeof insights.frequency === 'object' ? insights.frequency : null;
                const frequencyHtml = frequency
                    ? `<div class="flex flex-wrap gap-2 pt-2">${Object.entries(frequency).map(([subject, count]) => `<span class="rounded-lg bg-white px-3 py-1 text-xs font-semibold text-slate-800 ring-1 ring-slate-200">${subject}: ${count}</span>`).join('')}</div>`
                    : '';

                insightsBlock.innerHTML = `
                    ${summaryItems.join('') || '<p class="text-sm text-slate-700">No summary metrics provided.</p>'}
                    ${frequencyHtml}
                `;
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

            renderState(initialState);
            if ((initialState.status ?? 'pending') === 'pending') {
                pollStatus();
            }
        </script>
    </body>
</html>
