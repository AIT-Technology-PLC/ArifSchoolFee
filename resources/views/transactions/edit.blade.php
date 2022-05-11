@extends('layouts.app')

@section('title', 'Edit ' . $transaction->pad->name)

@section('content')
    <livewire:edit-transaction :transaction="$transaction->id" />
@endsection
