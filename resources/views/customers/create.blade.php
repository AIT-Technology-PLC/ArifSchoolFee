@extends('layouts.app')

@section('title', 'Create New Customer')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Customer" />
        <form
            id="formOne"
            action="{{ route('customers.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf

            @include('customers.partials.form', ['customer' => session()->getOldInput()])
        </form>
    </x-common.content-wrapper>
@endsection
