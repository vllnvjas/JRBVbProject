@extends('format.layout')

@section('title','Greetings')

@section('content')
<div class="card border-0">
    <div class="card-body p-4 p-md-5">
        <h2 class="mb-3">Greetings</h2>
        <p class="text-secondary mb-1">Welcome to your page.</p>
        <p class="mb-1"><strong>Name:</strong> {{ $name ?? 'Guest' }}</p>
        <p class="mb-0"><strong>Address:</strong> {{ $address ?? 'Not provided' }}</p>
    </div>
</div>
@endsection
