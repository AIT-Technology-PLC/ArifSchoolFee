@extends('layouts.app')

@section('title', 'Cache Setting')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-gear" />
                    <span>
                        Cache Setting
                    </span>
                </span>
            </x-slot>
        </x-content.header>
       
            <x-content.main>
                <x-common.success-message :message="session('successMessage')" />
                <div class="columns is-marginless is-centered is-multiline">
                    <div class="column is-3">
                        <form action="{{ route('admin.cache.clearViewCache') }}" method="get">
                            <x-common.button
                                tag="button"
                                mode="button"
                                type="submit"
                                id="clearButton"
                                icon="fas fa-remove"
                                label="Clear View Cache"
                                class="btn-softblue is-outlined is-small is-fullwidth"
                            />
                        </form>
                    </div>
                    <div class="column is-3">
                        <form action="{{ route('admin.cache.clearRouteCache') }}" method="get">
                            <x-common.button
                                tag="button"
                                mode="button"
                                type="submit"
                                id="clearButton"
                                icon="fas fa-remove"
                                label="Clear Route Cache"
                                class="btn-softblue is-outlined is-small is-fullwidth"
                            />
                        </form>
                    </div>
                    <div class="column is-3">
                        <form action="{{ route('admin.cache.clearConfigCache') }}" method="get">
                            <x-common.button
                                tag="button"
                                mode="button"
                                type="submit"
                                id="clearButton"
                                icon="fas fa-remove"
                                label="Clear Config Cache"
                                class="btn-softblue is-outlined is-small is-fullwidth"
                            />
                        </form>
                    </div>
                    <div class="column is-3">
                        <form action="{{ route('admin.cache.clearCache') }}" method="get">
                            <x-common.button
                                tag="button"
                                mode="button"
                                type="submit"
                                id="clearButton"
                                icon="fas fa-remove"
                                label="Clear Cache"
                                class="btn-softblue is-outlined is-small is-fullwidth"
                            />
                        </form>
                    </div>
                </div>
            </x-content.main>
    </x-common.content-wrapper>
@endsection
