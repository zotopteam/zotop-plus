<x-sidebar data="navbar::sidebar" :header="trans('navbar::module.title')" :active="$navbar_id ?? -1">
    <x-slot name="footer">
        <a href="{{route('navbar.navbar.index')}}">
            <i class="fa fa-cog"></i> {{trans('navbar::navbar.title')}}
        </a>
    </x-slot>
</x-sidebar>
