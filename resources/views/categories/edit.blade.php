@extends('layouts.app')

@section('title')
Edit Product Category
@endsection

@section('content')
<section class="mt-3 mx-3 m-lr-0">
    <div class="box radius-bottom-0 mb-0 has-background-white-bis">
        <h1 class="title text-green has-text-weight-medium is-size-5">
            Edit Product Category - {{ $category->name }}
        </h1>
    </div>
    <form action="{{ route('categories.update', $category->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <div class="columns is-marginless">
                <div class="column is-6">
                    <div class="field">
                        <label for="name" class="label text-green has-text-weight-normal">Name <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <input id="name" name="name" type="text" class="input" placeholder="Category Name" value="{{ $category->name }}">
                            <span class="icon is-small is-left">
                                <i class="fas fa-layer-group"></i>
                            </span>
                            @error('name')
                            <span class="help has-text-danger" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="field mt-5">
                        <label for="description" class="label text-green has-text-weight-normal">Description</label>
                        <div class="control has-icons-left">
                            <textarea name="description" id="description" cols="30" rows="10" class="textarea pl-6" placeholder="Description or note about the new category"> {{ $category->description }} </textarea>
                            <span class="icon is-large is-left">
                                <i class="fas fa-edit"></i>
                            </span>
                            @error('description')
                            <span class="help has-text-danger" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns is-marginless is-multiline">
                @foreach ($category->properties as $property)
                <div class="column is-6">
                    <div class="field">
                            <label for="properties[{{ $loop->index }}][{{ $property['key'] }}]" class="label text-green has-text-weight-normal">Property</label>
                            <div class="control">
                                <input id="properties[{{ $loop->index }}][{{ $property['key'] }}]" name="properties[{{ $loop->index }}][key]" type="text" class="input" value="{{ $property['key'] }}">
                            </div>
                        </div>
                    </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="properties[{{ $loop->index }}][{{ $property['value'] }}]" class="label text-green has-text-weight-normal">Data</label>
                        <div class="control">
                            <input id="properties[{{ $loop->index }}][{{ $property['value'] }}]" name="properties[{{ $loop->index }}][value]" type="text" class="input" value="{{ $property['value'] }}">
                        </div>
                    </div>
                </div>
                @endforeach
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
