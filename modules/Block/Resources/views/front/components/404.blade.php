<div class="text-error">

    @if ($id || $slug)
    @if ($id)
    ERROR: x-block id={{$id}} not found!
    @endif
    @if ($slug)
    ERROR: x-block slug={{$slug}} not found!
    @endif
    @else
    ERROR: x-block must be have id or slug attribute!
    @endif

</div>
