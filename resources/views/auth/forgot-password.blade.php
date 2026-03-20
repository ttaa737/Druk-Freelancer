@extends('layouts.guest')
@section('title', 'Forgot Password')
@section('content')

<div class="text-center mb-6">
    <div class="w-16 h-16 bg-druk-orange/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <i class="fa fa-key text-druk-orange text-2xl"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-800">Reset Your Password</h2>
    <p class="text-gray-500 text-sm mt-1">Enter your email and we'll send you a reset link.</p>
</div>

@if(session('status'))
<div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm mb-4">
    <i class="fa fa-check-circle text-green-500"></i> {{ session('status') }}
</div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full border @error('email') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-druk-orange/20 focus:border-druk-orange transition-all">
        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="w-full bg-druk-orange hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition-all hover:scale-[1.01] shadow-lg shadow-druk-orange/25">
        <i class="fa fa-paper-plane mr-2"></i>Send Reset Link
    </button>
</form>

<p class="text-center text-sm mt-5">
    <a href="{{ route('login') }}" class="font-medium text-druk-orange hover:text-orange-600 transition-colors">
        <i class="fa fa-arrow-left mr-1"></i>Back to Login
    </a>
</p>
@endsection
