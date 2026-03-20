@extends('layouts.app')
@section('title', 'Post a New Job')
@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('jobs.my') }}" class="text-decoration-none">My Jobs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Post a Job</li>
    </ol>
</nav>

@include('jobs._form')
@endsection
