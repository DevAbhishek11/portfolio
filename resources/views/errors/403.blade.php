@extends('layouts.app')
@section('content')
    <div
        style="min-height:80vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:2rem;position:relative;overflow:hidden;">
        <div class="orb"
            style="width:400px;height:400px;background:rgba(244,63,94,0.1);top:50%;left:50%;transform:translate(-50%,-50%);">
        </div>
        <div style="position:relative;z-index:1;">
            <div class="font-display"
                style="font-size:clamp(5rem,15vw,8rem);font-weight:900;line-height:1;background:linear-gradient(135deg,#f43f5e,#8b5cf6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                403</div>
            <h1 class="font-display"
                style="font-size:clamp(1.5rem,4vw,2.25rem);font-weight:800;color:var(--text-primary);margin:1rem 0;">Access
                Forbidden</h1>
            <p style="color:var(--text-secondary);margin-bottom:0.5rem;">アクセス禁止 · You don't have permission to view this
                page.</p>
            <p style="color:rgba(244,63,94,0.5);font-size:0.8rem;letter-spacing:0.1em;margin-bottom:2rem;">
                {{ $exception?->getMessage() }}</p>
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('home') }}" class="btn-anime">← Back to Home</a>
                <a href="{{ route('contact') }}" class="btn-outline">Contact Support</a>
            </div>
        </div>
    </div>
@endsection
