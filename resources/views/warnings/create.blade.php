@extends('layouts.app')

@section('title', 'Create Warnings')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Warning" />
        <form
            id="formOne"
            action="{{ route('warnings.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf

            @include('warnings.partials.details-form', ['data' => session()->getOldInput()])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection
