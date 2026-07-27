@extends('layouts.app')
@section('content')
    <div
        style="min-height:80vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:2rem;position:relative;overflow:hidden;">
        <div class="orb"
            style="width:500px;height:500px;background:rgba(244,63,94,0.08);top:50%;left:50%;transform:translate(-50%,-50%);">
        </div>
        <div style="position:relative;z-index:1;">
            <div class="font-display"
                style="font-size:clamp(5rem,15vw,8rem);font-weight:900;line-height:1;background:linear-gradient(135deg,#f43f5e,#eab308);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                500</div>
            <h1 class="font-display"
                style="font-size:clamp(1.5rem,4vw,2.25rem);font-weight:800;color:var(--text-primary);margin:1rem 0;">Server
                Error</h1>
            <p style="color:var(--text-secondary);margin-bottom:0.5rem;">エラーが発生しました · Something went wrong on our end.</p>
            <p style="color:rgba(244,63,94,0.4);font-size:0.8rem;margin-bottom:2.5rem;">We've been notified and are working
                on a fix.</p>
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('home') }}" class="btn-anime">← Back to Home</a>
                <button onclick="window.location.reload()" class="btn-outline">Try Again ↺</button>
            </div>
        </div>
    </div>
@endsection
