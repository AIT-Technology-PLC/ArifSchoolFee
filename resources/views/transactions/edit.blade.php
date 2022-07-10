@extends('layouts.app')

@section('title', 'Edit ' . str()->singular($transaction->pad->name))

@section('content')
    <livewire:edit-transaction :transaction="$transaction->id" />
@endsection
