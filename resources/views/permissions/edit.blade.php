@extends('layouts.app')

@section('title')
Set Employee Access Permissions
@endsection

@section('content')
<section class="mt-3 mx-3 m-lr-0">
    <div class="box radius-bottom-0 mb-0 has-background-white-bis">
        <h1 class="title text-green has-text-weight-medium is-size-5">
            Set Employee Access Permissions - {{ $permission->employee->user->name }}
        </h1>
    </div>
    <form action="{{ route('permissions.update', $permission->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <div class="columns is-marginless is-multiline">
                
            </div>
        </div>
        <div class="box radius-top-0">
            <div class="columns is-marginless">
                <div class="column is-paddingless">
                    <div class="buttons is-right">
                        <button class="button is-white text-green" type="reset">
                            <span class="icon">
                                <i class="fas fa-times"></i>
                            </span>
                            <span>
                                Cancel
                            </span>
                        </button>
                        <button class="button bg-green has-text-white">
                            <span class="icon">
                                <i class="fas fa-save"></i>
                            </span>
                            <span>
                                Save
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection
