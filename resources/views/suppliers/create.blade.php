@extends('layouts.app')

@section('title', 'Create New Supplier')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="New Supplier" />
        
        <livewire:supplier-form :has-redirect="true"/>

    </x-common.content-wrapper>
@endsection
