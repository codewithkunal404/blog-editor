@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="mx-auto w-full max-w-xl space-y-8 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-semibold text-slate-900">Create account</h1>
            <p class="text-sm text-slate-500">Register a new account to start authoring blog posts.</p>
        </div>

        <form method="POST" action="{{ route('register.submit') }}" class="space-y-6">
            @csrf

            <div class="space-y-3">
                <label class="block text-sm font-semibold text-slate-700">Name</label>
                <input name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-200" />
            </div>

            <div class="space-y-3">
                <label class="block text-sm font-semibold text-slate-700">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-200" />
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700">Password</label>
                    <input name="password" type="password" required class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-200" />
                </div>
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700">Confirm Password</label>
                    <input name="password_confirmation" type="password" required class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-200" />
                </div>
            </div>

            <button type="submit" class="w-full rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700">Register</button>
            <p class="text-center text-sm text-slate-500">Already have an account? <a href="{{ route('login') }}" class="font-semibold text-slate-900 hover:text-slate-700">Login</a></p>
        </form>
    </div>
@endsection
