@if ($projects->hasPages())
    <div style="display:flex;justify-content:center;gap:0.5rem;margin-top:3rem;flex-wrap:wrap;" id="project-pagination">
        {{ $projects->appends(request()->query())->links() }}
    </div>
@endif
