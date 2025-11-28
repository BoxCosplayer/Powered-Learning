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
                        href="{{ route('home') }}"
                        class="rounded-xl bg-gradient-to-r from-[#2d8f6f] via-[#2fa18e] to-[#ffb347] px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-[#2d8f6f33] transition hover:-translate-y-[1px] hover:shadow-[#2d8f6f4d] focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]"
                    >
                        Browse subjects
                    </a>
                </div>
            </header>
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
                <div class="relative flex flex-col justify-between overflow-hidden rounded-3xl bg-[#0f172a] text-white shadow-xl shadow-slate-300/60">
                    <div class="absolute -right-16 -top-12 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute -left-12 bottom-0 h-48 w-48 rounded-full bg-[#2fa18e]/35 blur-3xl"></div>
                    <div class="relative p-8 space-y-5">
                        <p class="text-sm font-semibold uppercase tracking-wide text-[#c8f2e2]">Security & session</p>
                        <h3 class="text-2xl font-semibold">Manage your access</h3>
                        <p class="text-sm leading-relaxed text-slate-200">
                            Keep your sign-in details current and return to the dashboard when you are ready to continue your learning journey.
                        </p>
                        <div class="grid gap-3">
                            <a
                                href="{{ route('dashboard') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-lg shadow-black/20 transition hover:-translate-y-[1px] focus:outline-none focus:ring-2 focus:ring-[#c8f2e2]"
                            >
                                Return to dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-white/40 bg-transparent px-4 py-3 text-sm font-semibold text-white backdrop-blur transition hover:-translate-y-[1px] hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-[#c8f2e2]"
                                >
                                    Log out securely
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="relative flex items-center gap-3 border-t border-white/10 bg-white/5 px-8 py-5 text-sm text-slate-100">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-base font-semibold">
                            PL
                        </span>
                        <div>
                            <p class="font-semibold">Powered Learning</p>
                            <p class="text-xs text-slate-200">Personalised pathways designed for you.</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
