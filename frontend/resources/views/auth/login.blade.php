<!-- 
File Purpose: Present the Laravel authentication login form with tailored styling while leaning on the framework’s built-in guards and session handling.
-->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} | Log in</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-[#f7f3e9] via-[#e7f4ff] to-[#e6f7f1] text-slate-900 antialiased">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -left-20 top-10 h-56 w-56 rounded-full bg-[#a7d9ff] blur-3xl opacity-40"></div>
            <div class="absolute -right-12 bottom-10 h-64 w-64 rounded-full bg-[#b1f1d0] blur-3xl opacity-30"></div>
        </div>
        <div class="relative flex min-h-screen items-center justify-center px-6 py-12">
            <div class="grid w-full max-w-5xl gap-10 lg:grid-cols-[1.05fr_0.95fr] items-center">
                <div class="backdrop-blur-sm bg-white/70 border border-white/60 shadow-[0_25px_70px_-35px_rgba(15,23,42,0.35)] rounded-2xl p-8 lg:p-10">
                    <p class="mb-3 inline-flex items-center gap-2 rounded-full bg-[#e7f1ff] px-4 py-2 text-xs font-semibold uppercase tracking-wide text-[#0f3e6e] shadow-sm">
                        Guided Sign-In
                        <span class="h-1.5 w-1.5 rounded-full bg-[#0f3e6e]"></span>
                    </p>
                    <h1 class="text-3xl font-semibold leading-tight text-slate-900 sm:text-4xl">
                        Welcome back to {{ config('app.name', 'Laravel') }}
                    </h1>
                    <p class="mt-4 max-w-2xl text-sm leading-relaxed text-slate-700">
                        Continue your powered learning journey with a quick, secure sign-in. Use your registered email and password, and we will take care of the rest!
                    </p>
                </div>
                <div class="relative">
                    <div class="absolute -inset-2 rounded-3xl bg-gradient-to-br from-[#0f3e6e] via-[#1f6f8b] to-[#3eb489] opacity-80 blur-lg"></div>
                    <div class="relative rounded-3xl bg-white p-8 shadow-2xl ring-1 ring-slate-100">
                        @if (session('status'))
                            <div class="mb-4 rounded-xl border border-[#1f6f8b] bg-[#e8f5ff] px-4 py-3 text-sm text-[#0f3e6e]">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-semibold text-slate-800">Email address</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    autofocus
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 shadow-inner shadow-slate-100 transition focus:border-[#1f6f8b] focus:outline-none focus:ring-4 focus:ring-[#1f6f8b1a]"
                                    placeholder="you@example.com"
                                />
                                @error('email')
                                    <p class="text-sm text-[#b42318]">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="password" class="block text-sm font-semibold text-slate-800">Password</label>
                                <div class="relative">
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        required
                                        autocomplete="current-password"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pr-28 text-sm font-medium text-slate-900 shadow-inner shadow-slate-100 transition focus:border-[#1f6f8b] focus:outline-none focus:ring-4 focus:ring-[#1f6f8b1a]"
                                        placeholder="Enter your password"
                                    />
                                    <button
                                        type="button"
                                        data-toggle-password
                                        data-target="password"
                                        class="absolute inset-y-1 right-1 rounded-lg bg-white px-3 text-xs font-semibold text-[#1f6f8b] shadow-sm ring-1 ring-slate-200 transition hover:bg-[#e8f5ff] focus:outline-none focus:ring-2 focus:ring-[#1f6f8b]"
                                    >
                                        Show
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-sm text-[#b42318]">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
                                <label class="inline-flex items-center gap-2 text-slate-800">
                                    <input
                                        name="remember"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-slate-300 text-[#1f6f8b] focus:ring-[#1f6f8b]"
                                        {{ old('remember') ? 'checked' : '' }}
                                    />
                                    <span class="font-semibold">Remember me</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a class="font-semibold text-[#1f6f8b] underline decoration-2 underline-offset-4 hover:text-[#0f3e6e]" href="{{ route('password.request') }}">
                                        Forgotten your password?
                                    </a>
                                @endif
                            </div>
                            <button
                                type="submit"
                                class="w-full rounded-xl bg-gradient-to-r from-[#0f3e6e] via-[#1f6f8b] to-[#3eb489] px-4 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-[#0f3e6e33] transition hover:translate-y-[-1px] hover:shadow-[#0f3e6e4d] active:translate-y-[0px]"
                            >
                                Log in securely
                            </button>
                            @if (Route::has('register'))
                                <p class="text-center text-sm text-slate-700">
                                    New here?
                                    <a class="font-semibold text-[#1f6f8b] underline decoration-2 underline-offset-4 hover:text-[#0f3e6e]" href="{{ route('register') }}">
                                        Create an account
                                    </a>
                                </p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.querySelectorAll('[data-toggle-password]').forEach((button) => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    if (!input) {
                        return;
                    }
                    const isHidden = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isHidden ? 'text' : 'password');
                    button.textContent = isHidden ? 'Hide' : 'Show';
                });
            });
        </script>
    </body>
</html>
