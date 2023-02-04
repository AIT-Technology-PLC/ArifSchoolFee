@extends('layouts.app')

@section('title', 'Create Price')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Price" />
        <form
            id="formOne"
            action="{{ route('prices.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf

            @include('prices.partials.details-form', [
                'data' => session()->getOldInput(),
                'productId' => null,
            ])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection
