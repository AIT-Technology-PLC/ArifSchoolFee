@extends('layouts.app')

@section('title', 'Create ' . $pad->name)

@section('content')
    <livewire:create-transaction :pad="$pad->id" />
@endsection
