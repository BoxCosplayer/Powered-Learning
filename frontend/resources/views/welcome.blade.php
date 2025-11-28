<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Powered Learning') }} | Home</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-[#f7f3e9] via-[#e7f4ff] to-[#e6f7f1] text-slate-900 antialiased">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -left-24 top-10 h-56 w-56 rounded-full bg-[#a7d9ff] blur-3xl opacity-40"></div>
            <div class="absolute -right-16 bottom-16 h-64 w-64 rounded-full bg-[#b1f1d0] blur-3xl opacity-35"></div>
        </div>
        <div class="relative flex min-h-screen items-center justify-center px-6 py-12">
            <div class="max-w-3xl w-full rounded-3xl bg-white/80 p-10">
                <div class="flex flex-col gap-6 text-center">
                    <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">
                        {{ config('app.name', 'Powered Learning') }}
                    </h1>
                    @auth
                        <p class="text-lg font-medium text-slate-800">
                            Welcome back, {{ auth()->user()->name }}. You are signed in and can continue your powered learning journey.
                        </p>
                        <div class="flex flex-wrap justify-center gap-3">
                            <a
                                href="{{ route('dashboard') }}"
                                class="items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold uppercase tracking-wide text-slate-900 shadow-md shadow-slate-200 transition hover:-translate-y-px hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#1f6f8b]"
                            >
                                Go to dashboard
                            </a>
                            <a
                                href="{{ route('logout') }}"
                                class="items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold uppercase tracking-wide text-slate-900 shadow-md shadow-slate-200 transition hover:-translate-y-px hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#1f6f8b]"
                            >
                                Logout
                            </a>
                        </div>
                    @endauth
                    @guest
                        <p class="text-lg text-slate-700">
                            Sign in to pick up where you left off and see your personalised recommendations.
                        </p>
                        <div class="flex justify-center">
                            <a
                                href="{{ route('login') }}"
                                class="items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold uppercase tracking-wide text-slate-900 shadow-md shadow-slate-200 transition hover:-translate-y-px hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#1f6f8b]"
                            >
                                Go to login
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </body>
</html>
