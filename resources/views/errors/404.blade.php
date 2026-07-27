@extends('layouts.app')
@section('content')
    <div
        style="min-height:80vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:2rem;position:relative;overflow:hidden;">
        <div class="orb"
            style="width:500px;height:500px;background:rgba(139,92,246,0.1);top:50%;left:50%;transform:translate(-50%,-50%);">
        </div>
        <div style="position:relative;z-index:1;">
            <div class="font-display"
                style="font-size:clamp(5rem,15vw,8rem);font-weight:900;line-height:1;background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                404</div>
            <h1 class="font-display"
                style="font-size:clamp(1.5rem,4vw,2.25rem);font-weight:800;color:var(--text-primary);margin:1rem 0;">Page Not
                Found</h1>
            <p style="color:var(--text-secondary);margin-bottom:0.5rem;">迷子になりました · The page you're looking for doesn't
                exist.</p>
            <p style="color:rgba(139,92,246,0.4);font-size:0.8rem;letter-spacing:0.1em;margin-bottom:2.5rem;">
                {{ request()->path() !== '/' ? '"/' . request()->path() . '"' : '' }}
            </p>
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('home') }}" class="btn-anime">← Back to Home</a>
                <a href="{{ route('projects.index') }}" class="btn-outline">View Projects</a>
            </div>
            <p style="color:rgba(139,92,246,0.25);font-size:0.75rem;margin-top:3rem;letter-spacing:0.15em;">ページが見つかりません</p>
        </div>
    </div>
@endsection
