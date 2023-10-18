@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Supplier" />

        <livewire:supplier-form
            :has-redirect="true"
            :supplier="$supplier"
            method="update"
        />

    </x-common.content-wrapper>
@endsection
