<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Powered Learning') }} | Profile</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-[#e8f3ff] via-[#fef6ec] to-[#e8f7ef] text-slate-900 antialiased">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -left-14 top-8 h-60 w-60 rounded-full bg-[#2d8f6f] blur-3xl opacity-20"></div>
            <div class="absolute -right-16 bottom-16 h-64 w-64 rounded-full bg-[#ffb347] blur-3xl opacity-25"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_25%_20%,rgba(45,143,111,0.08),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(255,179,71,0.08),transparent_30%)]"></div>
        </div>
        <main class="relative mx-auto flex min-h-screen max-w-6xl flex-col px-6 py-10">
            <header class="flex items-center justify-between gap-4 rounded-2xl bg-white/85 px-6 py-4 shadow-lg shadow-slate-200/60 ring-1 ring-slate-100 backdrop-blur">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Your account</p>
                    <h1 class="text-3xl font-semibold leading-tight text-slate-900">Profile overview</h1>
                </div>
                <div class="flex flex-wrap items-center gap-3">
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
            @if(session('status'))
                <div class="mt-6 rounded-2xl border border-[#2d8f6f]/20 bg-[#e7f7ef] px-4 py-3 text-sm font-semibold text-[#1f6f54] shadow-sm shadow-slate-200/60">
                    {{ session('status') }}
                </div>
            @endif
            <section class="relative mt-10 grid flex-1 gap-8 md:grid-cols-[1.1fr_0.9fr]">
                <div class="relative overflow-hidden rounded-3xl bg-white/90 p-8 shadow-xl shadow-slate-200/70 ring-1 ring-slate-100 backdrop-blur">
                    <div class="absolute -left-12 -top-10 h-44 w-44 rounded-full bg-[#2d8f6f] opacity-10 blur-2xl"></div>
                    <div class="absolute -right-14 bottom-8 h-48 w-48 rounded-full bg-[#ffb347] opacity-10 blur-3xl"></div>
                    <div class="relative space-y-4">
                        <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Your details</p>
                        <h2 class="text-2xl font-semibold text-slate-900">Hello, {{ $user->name }}</h2>
                        <p class="text-sm leading-relaxed text-slate-700">
                            Review your key account information below. These details keep your personalised recommendations aligned with the right learner profile.
                        </p>
                        <div class="grid gap-4 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 md:grid-cols-2">
                            <div class="space-y-1">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">Full name</p>
                                <p class="text-base font-semibold text-slate-900">{{ $user->name }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">Email</p>
                                <p class="text-base font-semibold text-slate-900">{{ $user->email }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">Account status</p>
                                <p class="inline-flex items-center gap-2 rounded-full bg-[#e7f7ef] px-3 py-1 text-sm font-semibold text-[#2d8f6f]">
                                    <span class="h-2.5 w-2.5 rounded-full bg-[#2d8f6f]"></span>
                                    Active
                                </p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">Member since</p>
                                <p class="text-base font-semibold text-slate-900">
                                    {{ optional($user->created_at)->format('j F Y') ?? 'Not available' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-3xl bg-white/90 p-8 shadow-xl shadow-slate-200/70 ring-1 ring-slate-100 backdrop-blur">
                    <div class="absolute -right-16 -top-12 h-48 w-48 rounded-full bg-[#ffb347] opacity-10 blur-3xl"></div>
                    <div class="absolute -left-12 bottom-0 h-52 w-52 rounded-full bg-[#2d8f6f] opacity-10 blur-3xl"></div>
                    <div class="relative space-y-5">
                        <div class="space-y-1">
                            <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Predicted grades</p>
                            <h2 class="text-2xl font-semibold text-slate-900">Add a new entry</h2>
                            <p class="text-sm leading-relaxed text-slate-700">
                                Log a projected score or confidence level for any subject. New subjects will be created automatically so your predictions stay complete. /100
                            </p>
                        </div>
                        <form method="POST" action="{{ route('profile.predicted-grades.store') }}" class="space-y-4">
                            @csrf
                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="flex flex-col gap-1 text-sm font-semibold text-slate-800">
                                    <span class="text-xs uppercase tracking-wide text-slate-600">Subject name</span>
                                    <input
                                        type="text"
                                        name="subject_name"
                                        value="{{ old('subject_name') }}"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm transition focus:border-[#2d8f6f] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]/40"
                                        placeholder="e.g. Further Mathematics"
                                        required
                                    />
                                    @error('subject_name')
                                        <span class="text-xs font-normal text-[#b42318]">{{ $message }}</span>
                                    @enderror
                                </label>
                                <label class="flex flex-col gap-1 text-sm font-semibold text-slate-800">
                                    <span class="text-xs uppercase tracking-wide text-slate-600">Predicted score or Confidence level</span>
                                    <input
                                        type="number"
                                        name="predicted_score"
                                        value="{{ old('predicted_score') }}"
                                        min="0"
                                        max="100"
                                        step="0.1"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm transition focus:border-[#2d8f6f] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]/40"
                                        placeholder="e.g. 82.5"
                                        required
                                    />
                                    @error('predicted_score')
                                        <span class="text-xs font-normal text-[#b42318]">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <p class="text-xs text-slate-600">Entries save straight to your profile so the recommender can learn from them.</p>
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#2d8f6f] via-[#2fa18e] to-[#ffb347] px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-[#2d8f6f33] transition hover:-translate-y-[1px] hover:shadow-[#2d8f6f4d] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]"
                                >
                                    Save score
                                </button>
                            </div>
                        </form>
                        <div class="space-y-4 rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="space-y-1">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-[#2d8f6f]">Saved predictions</p>
                                    <p class="text-sm leading-relaxed text-slate-700">
                                        Every prediction linked to your account so the recommender can keep learning.
                                    </p>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#2d8f6f]">
                                    {{ $predictedGrades->count() }} total
                                </span>
                            </div>
                            @if($predictedGrades->isEmpty())
                                <div class="rounded-xl border border-dashed border-slate-200 bg-white/70 px-4 py-3 text-sm font-semibold text-slate-600">
                                    No predicted grades saved yet. Add your first entry above to get started.
                                </div>
                            @else
                                <div class="divide-y divide-slate-200 rounded-xl border border-slate-200 bg-white/90">
                                    @foreach($predictedGrades as $predictedGrade)
                                        <div class="flex items-center justify-between gap-3 px-4 py-3">
                                            <div class="space-y-0.5">
                                                <p class="text-sm font-semibold text-slate-900">
                                                    {{ optional($predictedGrade->subject)->name ?? 'Subject unavailable' }}
                                                </p>
                                                <p class="text-xs text-slate-600">Recorded against your profile</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-semibold text-[#2d8f6f]">
                                                    {{ rtrim(rtrim(number_format($predictedGrade->score, 2, '.', ''), '0'), '.') }}/100
                                                </p>
                                                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Predicted grade</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            <section class="relative mt-10">
                <div class="relative overflow-hidden rounded-3xl bg-white/90 p-8 shadow-xl shadow-slate-200/70 ring-1 ring-slate-100 backdrop-blur">
                    <div class="absolute -left-12 -top-10 h-44 w-44 rounded-full bg-[#2d8f6f] opacity-10 blur-2xl"></div>
                    <div class="absolute -right-14 bottom-8 h-48 w-48 rounded-full bg-[#ffb347] opacity-10 blur-3xl"></div>
                    <div class="relative space-y-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="space-y-1">
                                <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Study history</p>
                                <h2 class="text-2xl font-semibold text-slate-900">Manage your activity log</h2>
                                <p class="text-sm leading-relaxed text-slate-700">
                                    Review, add, and adjust the entries the recommender learns from. Scroll the table to see everything saved on your account.
                                </p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-[#e7f7ef] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#2d8f6f]">
                                {{ $historyEntries->count() }} entries
                            </span>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                            <form method="POST" action="{{ route('profile.history.store') }}" class="space-y-4">
                                @csrf
                                <div class="grid gap-4 md:grid-cols-4">
                                    <label class="flex flex-col gap-1 text-sm font-semibold text-slate-800">
                                        <span class="text-xs uppercase tracking-wide text-slate-600">Subject</span>
                                        <select
                                            name="subject_name"
                                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm transition focus:border-[#2d8f6f] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]/40"
                                            required
                                        >
                                            <option value="" disabled {{ old('subject_name') === null ? 'selected' : '' }}>Select subject</option>
                                            @foreach($subjects as $subjectOption)
                                                <option value="{{ $subjectOption->name }}" @selected(old('subject_name') === $subjectOption->name)>{{ $subjectOption->name }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label class="flex flex-col gap-1 text-sm font-semibold text-slate-800">
                                        <span class="text-xs uppercase tracking-wide text-slate-600">Type</span>
                                        <select
                                            name="type_id"
                                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm transition focus:border-[#2d8f6f] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]/40"
                                            required
                                        >
                                            <option value="" disabled {{ old('type_id') === null ? 'selected' : '' }}>Select type</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->uuid }}" @selected(old('type_id') === $type->uuid)>{{ $type->type }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label class="flex flex-col gap-1 text-sm font-semibold text-slate-800">
                                        <span class="text-xs uppercase tracking-wide text-slate-600">Score</span>
                                        <input
                                            type="number"
                                            name="score"
                                            value="{{ old('score') }}"
                                            min="0"
                                            max="100"
                                            step="0.1"
                                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm transition focus:border-[#2d8f6f] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]/40"
                                            placeholder="e.g. 74.5"
                                            required
                                        />
                                    </label>
                                    <label class="flex flex-col gap-1 text-sm font-semibold text-slate-800">
                                        <span class="text-xs uppercase tracking-wide text-slate-600">Studied at</span>
                                        <input
                                            type="date"
                                            name="studied_at"
                                            value="{{ old('studied_at') }}"
                                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm transition focus:border-[#2d8f6f] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]/40"
                                            required
                                        />
                                    </label>
                                </div>
                                @error('subject_name')
                                    <span class="block text-xs font-normal text-[#b42318]">{{ $message }}</span>
                                @enderror
                                @error('type_id')
                                    <span class="block text-xs font-normal text-[#b42318]">{{ $message }}</span>
                                @enderror
                                @error('score')
                                    <span class="block text-xs font-normal text-[#b42318]">{{ $message }}</span>
                                @enderror
                                @error('studied_at')
                                    <span class="block text-xs font-normal text-[#b42318]">{{ $message }}</span>
                                @enderror
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <p class="text-xs text-slate-600">Add fresh activity to tune future personalised sessions.</p>
                                    <button
                                        type="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#2d8f6f] via-[#2fa18e] to-[#ffb347] px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-[#2d8f6f33] transition hover:-translate-y-[1px] hover:shadow-[#2d8f6f4d] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]"
                                    >
                                        Save history entry
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="space-y-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-800">Your recorded sessions</p>
                                <span class="text-xs text-slate-600">Scroll to view or edit every entry.</span>
                            </div>
                            @if($historyEntries->isEmpty())
                                <div class="rounded-xl border border-dashed border-slate-200 bg-white/70 px-4 py-3 text-sm font-semibold text-slate-600">
                                    No history saved yet. Log your first session above to populate the table.
                                </div>
                            @else
                                <div class="max-h-[420px] overflow-y-auto rounded-xl border border-slate-200 bg-white/90 shadow-sm shadow-slate-200/50">
                                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                                        <thead class="bg-slate-50/80 text-xs uppercase tracking-wide text-slate-600">
                                            <tr>
                                                <th class="px-4 py-3 text-left font-semibold">Subject</th>
                                                <th class="px-4 py-3 text-left font-semibold">Type</th>
                                                <th class="px-4 py-3 text-left font-semibold">Score (/100)</th>
                                                <th class="px-4 py-3 text-left font-semibold">Studied at</th>
                                                <th class="px-4 py-3 text-right font-semibold">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($historyEntries as $historyEntry)
                                                @php
                                                    $updateFormId = 'history-update-' . $historyEntry->historyEntryID;
                                                    $deleteFormId = 'history-delete-' . $historyEntry->historyEntryID;
                                                    $studiedAtValue = optional($historyEntry->studied_at)?->format('Y-m-d');
                                                @endphp
                                                <tr class="bg-white/80 text-slate-900 transition hover:bg-slate-50">
                                                    <td class="px-4 py-3 align-middle">
                                                        <select
                                                            form="{{ $updateFormId }}"
                                                            name="subject_name"
                                                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm transition focus:border-[#2d8f6f] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]/40"
                                                            required
                                                        >
                                                            @foreach($subjects as $subjectOption)
                                                                <option value="{{ $subjectOption->name }}" @selected(optional($historyEntry->subject)->name === $subjectOption->name)>{{ $subjectOption->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="px-4 py-3 align-middle">
                                                        <select
                                                            form="{{ $updateFormId }}"
                                                            name="type_id"
                                                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm transition focus:border-[#2d8f6f] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]/40"
                                                            required
                                                        >
                                                            @foreach($types as $type)
                                                                <option value="{{ $type->uuid }}" @selected($historyEntry->type?->uuid === $type->uuid)>{{ $type->type }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="px-4 py-3 align-middle">
                                                        <input
                                                            form="{{ $updateFormId }}"
                                                            type="number"
                                                            name="score"
                                                            value="{{ number_format($historyEntry->score, 2, '.', '') }}"
                                                            min="0"
                                                            max="100"
                                                            step="0.1"
                                                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm transition focus:border-[#2d8f6f] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]/40"
                                                            required
                                                        />
                                                    </td>
                                                    <td class="px-4 py-3 align-middle">
                                                        <input
                                                            form="{{ $updateFormId }}"
                                                            type="date"
                                                            name="studied_at"
                                                            value="{{ $studiedAtValue }}"
                                                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm transition focus:border-[#2d8f6f] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]/40"
                                                            required
                                                        />
                                                    </td>
                                                    <td class="px-4 py-3 align-middle">
                                                        <form id="{{ $updateFormId }}" method="POST" action="{{ route('profile.history.update', $historyEntry->historyEntryID) }}">
                                                            @csrf
                                                            @method('PUT')
                                                        </form>
                                                        <form id="{{ $deleteFormId }}" method="POST" action="{{ route('profile.history.destroy', $historyEntry->historyEntryID) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <div class="flex items-center justify-end gap-2">
                                                            <button
                                                                form="{{ $updateFormId }}"
                                                                type="submit"
                                                                class="inline-flex items-center rounded-lg bg-[#2d8f6f] px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:-translate-y-[1px] hover:bg-[#277a60] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]"
                                                            >
                                                                Update
                                                            </button>
                                                            <button
                                                                form="{{ $deleteFormId }}"
                                                                type="submit"
                                                                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-[#b42318] shadow-sm transition hover:-translate-y-[1px] hover:border-[#b42318] hover:bg-[#fff6f5] focus:outline-none focus:ring-2 focus:ring-[#b42318]/40"
                                                                onclick="return confirm('Are you sure you want to delete this entry?');"
                                                            >
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
