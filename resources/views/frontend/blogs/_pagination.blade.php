@if ($blogs->hasPages())
    <div style="display:flex;justify-content:center;margin-top:3rem;" id="blog-pagination">
        {{ $blogs->appends(request()->query())->links() }}
    </div>
@endif
