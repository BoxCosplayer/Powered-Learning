{{-- 
    Dashboard view presenting authenticated user info and navigation.
    Inputs: $user App\Models\User instance for the authenticated user.
    Outputs: HTML page with navigation links and guidance to open the study workspace.
--}} 
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Powered Learning') }} | Dashboard</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-[#f5f1e8] via-[#e4f4ff] to-[#e3f7f0] text-slate-900 antialiased">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -left-16 top-10 h-56 w-56 rounded-full bg-[#2d8f6f] blur-3xl opacity-20"></div>
            <div class="absolute -right-12 bottom-12 h-64 w-64 rounded-full bg-[#ffb347] blur-3xl opacity-25"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(45,143,111,0.08),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(255,179,71,0.08),transparent_30%)]"></div>
        </div>
        <main class="relative mx-auto flex min-h-screen max-w-6xl flex-col justify-between px-6 py-10">
            <header class="flex items-center justify-between gap-4 rounded-2xl bg-white/80 px-6 py-4 shadow-lg shadow-slate-200/60 ring-1 ring-slate-100 backdrop-blur">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Welcome back</p>
                    <h1 class="text-3xl font-semibold leading-tight text-slate-900">Powered Learning Dashboard</h1>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a
                        href="{{ route('profile') }}"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:-translate-y-[1px] hover:shadow-md hover:shadow-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]"
                    >
                        View profile
                    </a>
                    <a
                        href="{{ route('home') }}"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:-translate-y-[1px] hover:shadow-md hover:shadow-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]"
                    >
                        Go to homepage
                    </a>
                    <a
                        href="{{ route('logout') }}"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:-translate-y-[1px] hover:shadow-md hover:shadow-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2d8f6f]"
                    >
                        Logout
                    </a>
                </div>
            </header>
            <section class="relative mt-10 grid flex-1 gap-8 md:grid-cols-[1.1fr_0.9fr]">
                <div class="space-y-6">
                    <div class="relative overflow-hidden rounded-3xl bg-white/90 p-8 shadow-xl shadow-slate-200/70 ring-1 ring-slate-100 backdrop-blur">
                        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-[#ffb347] opacity-10 blur-2xl"></div>
                        <div class="absolute -left-14 -bottom-14 h-48 w-48 rounded-full bg-[#2d8f6f] opacity-10 blur-2xl"></div>
                        <div class="relative space-y-4">
                            <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Your profile</p>
                            <h2 class="text-2xl font-semibold text-slate-900">Hello, {{ $user->name }}</h2>
                            <p class="text-sm leading-relaxed text-slate-700">
                                You are signed in and ready to continue your Powered Learning journey. Use the links here to jump back to the homepage or sign out securely.
                            </p>
                            <div class="grid gap-4 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 md:grid-cols-2">
                                <div class="space-y-1">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">Account</p>
                                    <p class="text-base font-semibold text-slate-900">{{ $user->email }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">Status</p>
                                    <p class="inline-flex items-center gap-2 rounded-full bg-[#e7f7ef] px-3 py-1 text-sm font-semibold text-[#2d8f6f]">
                                        <span class="h-2.5 w-2.5 rounded-full bg-[#2d8f6f]"></span>
                                        Authenticated
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative overflow-hidden rounded-3xl bg-white/90 p-8 shadow-xl shadow-slate-200/70 ring-1 ring-slate-100 backdrop-blur">
                        <div class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-[#2d8f6f] opacity-10 blur-2xl"></div>
                        <div class="absolute -right-16 bottom-10 h-44 w-44 rounded-full bg-[#ffb347] opacity-20 blur-3xl"></div>
                        <div class="relative space-y-6">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <p class="text-sm font-semibold uppercase tracking-wide text-[#2d8f6f]">Study workspace</p>
                                    <h2 class="text-2xl font-semibold text-slate-900">Generate from the study page</h2>
                                    <p class="text-sm leading-relaxed text-slate-700">
                                        Configure timings and run the personalised recommender directly from the study page. Your queued subjects and next steps live there.
                                    </p>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-[#e7f7ef] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#2d8f6f]">Next stop</span>
                            </div>
                            <div class="space-y-4 rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                                <p class="text-sm text-slate-700">
                                    Head to the study view to set session time, breaks, session count, and shots before you generate. You can also step through the queue from there.
                                </p>
                                <div class="flex flex-wrap items-center gap-3">
                                    <a
                                        href="{{ route('study.show') }}"
                                        class="inline-flex items-center gap-2 rounded-xl bg-[#2d8f6f] px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-[#2d8f6f]/30 transition hover:-translate-y-[1px] hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2d8f6f]"
                                    >
                                        Open study page
                                    </a>
                                    <span class="text-sm font-semibold text-slate-700">Queue controls and generator now live on study</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
