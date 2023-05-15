@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="Edit Customer" />

        <livewire:customer-form
            :has-redirect="true"
            :customer="$customer"
            method="update"
        />

    </x-common.content-wrapper>
@endsection
