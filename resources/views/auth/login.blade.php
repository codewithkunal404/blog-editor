@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="mx-auto w-full max-w-xl space-y-8 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-semibold text-slate-900">Welcome back</h1>
            <p class="text-sm text-slate-500">Sign in to manage your blog content.</p>
        </div>

        <form method="POST" action="{{ route('login.submit') }}" class="space-y-6">
            @csrf

            <div class="space-y-3">
                <label class="block text-sm font-semibold text-slate-700">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-200" />
            </div>

            <div class="space-y-3">
                <label class="block text-sm font-semibold text-slate-700">Password</label>
                <input name="password" type="password" required class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-200" />
            </div>

            <div class="flex items-center justify-between text-sm text-slate-500">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500" />
                    Remember me
                </label>
                <a href="{{ route('register') }}" class="font-semibold text-slate-900 hover:text-slate-700">Create account</a>
            </div>

            <button type="submit" class="w-full rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700">Login</button>
        </form>
    </div>
@endsection
