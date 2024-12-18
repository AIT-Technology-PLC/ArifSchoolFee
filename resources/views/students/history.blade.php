@extends('layouts.app')

@section('title', 'Student Data History')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="{{ str($student->first_name)->append(' '.$student->father_name ?? null )}}">
        </x-content.header>
        <x-content.footer>
            <div> {{ $dataTable->table() }} </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
