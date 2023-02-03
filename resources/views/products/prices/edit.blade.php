@extends('layouts.app')

@section('title', 'Edit Price')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Price" />
        <form
            id="formOne"
            action="{{ route('prices.update', $prices->first()->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')

            @include('prices.partials.details-form', ['data' => ['price' => old('price') ?? $prices]])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection
