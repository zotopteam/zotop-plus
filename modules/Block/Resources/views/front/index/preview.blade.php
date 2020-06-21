@extends('layouts.dialog')

@section('content')
<div class="block-preview m-2">
    <x-block :id="$id" />
</div>
@endsection
