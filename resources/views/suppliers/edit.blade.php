@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Supplier" />
        <form
            id="formOne"
            action="{{ route('suppliers.update', $supplier->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')

            @include('suppliers.partials.form', ['supplier' => $supplier])

        </form>
    </x-common.content-wrapper>
@endsection
