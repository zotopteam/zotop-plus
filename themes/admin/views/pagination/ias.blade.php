@if ($paginator->hasPages())
<div class="ias-pagination" role="navigation">
    @if ($paginator->hasMorePages())
    <a class="ias-next" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
    @endif
</div>

@push('js')
<script src="{{Theme::asset('js/infinite-ajax-scroll.min.js')}}"></script>
<script>
    var ias = new InfiniteAjaxScroll('.ias-container', {
        scrollContainer: '.ias-scrollable',
        spinner: {
            element: '.ias-spinner',
            delay: 600,
            show: function(element) {
                $(element).removeClass('d-none');
            },
            hide: function(element) {
                $(element).addClass('d-none');
            }
        },
        item: '.ias-item',
        next: '.ias-next',
        pagination: '.ias-pagination'
    });
</script>
@endpush
@endif
