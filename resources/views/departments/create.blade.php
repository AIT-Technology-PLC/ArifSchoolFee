@extends('layouts.app')

@section('title', 'Create New Department')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Department" />
        <form
            id="formOne"
            action="{{ route('departments.store') }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf

            @include('departments.partials.details-form', ['data' => session()->getOldInput()])

            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection
