@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Customer" />
        <form
            id="formOne"
            action="{{ route('customers.update', $customer->id) }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')

            @include('customers.partials.form', ['customer' => $customer])

        </form>
    </x-common.content-wrapper>
@endsection
