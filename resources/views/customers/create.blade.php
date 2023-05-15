@extends('layouts.app')

@section('title', 'Create New Customer')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Customer" />
        
        <livewire:customer-form :has-redirect="true"/>

    </x-common.content-wrapper>
@endsection
