@extends('layouts.app')

@section('title')
    Permission Management
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Permission Management - {{ $employee->user->name }}
            </h1>
        </div>

        <form id="formOne" action="{{ route('permissions.update', $employee->id) }}" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <x-common.success-message :message="session('message')" />
                <div class="columns is-marginless is-multiline">
                    @foreach ($permissionCategories as $key => $value)
                        @continue(!isFeatureEnabled($value['feature']))
                        <div class="column is-6">
                            <div class="message">
                                <div class="message-header has-background-white-ter text-gold">
                                    {{ $value['label'] }} Permissions
                                </div>
                                <div class="message-body has-background-white-bis">
                                    <div class="columns is-marginless is-multiline">
                                        @foreach ($permissionsByCategories[$key] as $permission)
                                            <div class="column is-one-third">
                                                <div class="field">
                                                    <div class="control">
                                                        <label class="checkbox text-green has-text-weight-normal is-size-7">
                                                            <input type="checkbox" name="permissions[]" value="{{ $permission }}" {{ $userDirectPermissions->contains($permission) ? 'checked' : '' }}>
                                                            {{ $permission }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="box radius-top-0">
                <x-common.save-button />
            </div>
        </form>
    </section>
@endsection
