@extends('layouts.app')

@section('title')
    Modify Employee Data
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5 is-capitalize">
                Modify {{ $employee->user->name }}'s Information
            </h1>
        </div>
        <form id="formOne" action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="name" class="label text-green has-text-weight-normal">Name <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="name" name="name" type="text" class="input" placeholder="Employee Name" value="{{ $employee->user->name }}" autocomplete="name" autofocus>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-user"></i>
                                </span>
                                @error('name')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="email" class="label text-green has-text-weight-normal">Email <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="email" name="email" type="text" class="input" placeholder="Email Address" value="{{ $employee->user->email }}" autocomplete="email">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-at"></i>
                                </span>
                                @error('email')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="position" class="label text-green has-text-weight-normal">Job Title/Position <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <input id="position" name="position" type="text" class="input" placeholder="Job Title" value="{{ $employee->position }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-user-tie"></i>
                                </span>
                                @error('position')
                                    <span class="help has-text-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns is-marginless is-multiline">
                    @if (!$employee->user->hasRole('System Manager'))
                        <div class="column is-6">
                            <div class="field">
                                <label for="enabled" class="label text-green has-text-weight-normal"> Can this employee access the system? <sup class="has-text-danger">*</sup> </label>
                                <div class="control">
                                    <label class="radio has-text-grey has-text-weight-normal">
                                        <input type="radio" name="enabled" value="1" class="mt-3" {{ $employee->enabled ? 'checked' : '' }}>
                                        Yes, this employee can access the system
                                    </label>
                                    <br>
                                    <label class="radio has-text-grey has-text-weight-normal mt-2">
                                        <input type="radio" name="enabled" value="0" {{ $employee->enabled ? '' : 'checked' }}>
                                        No, this employee can't access the system
                                    </label>
                                    @error('enabled')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (!$employee->user->hasRole('System Manager'))
                        <div class="column is-6">
                            <div class="field">
                                <label for="role" class="label text-green has-text-weight-normal"> Choose Role <sup class="has-text-danger">*</sup> </label>
                                <div class="control">
                                    @foreach ($roles as $role)
                                        <label class="radio has-text-grey has-text-weight-normal">
                                            <input type="radio" name="role" value="{{ $role->name }}" class="mt-3" {{ $employee->user->roles[0]->name == $role->name ? 'checked' : '' }}>
                                            {{ $role->name }}
                                        </label>
                                        <br>
                                    @endforeach
                                    @error('role')
                                        <span class="help has-text-danger" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
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
                            <button id="saveButton" class="button bg-green has-text-white">
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
