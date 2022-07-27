@extends('layouts.app')

@section('title', 'Create ' . str()->singular($pad->name))

@section('content')
    <livewire:create-transaction
        :pad="$pad->id"
        :master="old('master', [])"
        :details="old('details', [[]])"
    />
@endsection
