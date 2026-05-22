@extends('format.layout')

@section('title','About')

@section('content')
<div class="card border-0">
    <div class="card-body p-4 p-md-5">
        <h2 class="mb-3">About This System</h2>
        <p class="text-secondary mb-4">This student dashboard helps you keep records organized with a clean interface designed for quick daily use.</p>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded-4 border bg-white h-100">
                    <h6 class="mb-2">Fast Entry</h6>
                    <p class="text-secondary mb-0 small">Add student details in a few fields with guided form inputs.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-4 border bg-white h-100">
                    <h6 class="mb-2">Easy Updates</h6>
                    <p class="text-secondary mb-0 small">Edit records with the same structure used when creating them.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-4 border bg-white h-100">
                    <h6 class="mb-2">Clear Review</h6>
                    <p class="text-secondary mb-0 small">View full student details in a simple, readable layout.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
