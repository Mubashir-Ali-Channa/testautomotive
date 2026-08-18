@extends('layouts.admin')

@section('content')
    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h2 style="font-size: 1.8rem; text-transform: uppercase;">Resume Viewer</h2>
            <p class="text-muted">Applicant: <strong class="text-primary">{{ $application->name }}</strong> | Position: <strong>{{ $application->career->title ?? 'Deleted Position' }}</strong></p>
        </div>
        <a href="{{ route('admin.leads') }}" class="btn btn-secondary" style="height: 40px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-arrow-left"></i> Back to Inbox
        </a>
    </div>

    <div class="card" style="padding: 0; overflow: hidden; height: calc(100vh - 200px); border: 1px solid var(--border-light); box-shadow: var(--shadow);">
        <iframe src="{{ asset('storage/' . $application->resume_path) }}" style="width: 100%; height: 100%; border: none;"></iframe>
    </div>
@endsection
