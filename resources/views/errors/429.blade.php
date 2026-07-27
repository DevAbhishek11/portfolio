@extends('layouts.app')
@section('content')
    <div style="min-height:80vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:2rem;">
        <div>
            <div class="font-display grad-text" style="font-size:6rem;font-weight:900;line-height:1;">429</div>
            <h1 class="font-display" style="font-size:2rem;font-weight:800;color:var(--text-primary);margin:1rem 0;">Too Many
                Requests</h1>
            <p style="color:var(--text-secondary);margin-bottom:2rem;">You're moving too fast! Please slow down and try again
                shortly.</p>
            <a href="{{ route('home') }}" class="btn-anime">← Back to Home</a>
        </div>
    </div>
@endsection
