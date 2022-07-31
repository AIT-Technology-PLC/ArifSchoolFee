@extends('layouts.app')

@section('title', 'Create New Supplier')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Supplier" />
        <form
            id="formOne"
            action="{{ route('suppliers.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf

            @include('suppliers.partials.form', ['supplier' => session()->getOldInput()])

        </form>
    </x-common.content-wrapper>
@endsection
