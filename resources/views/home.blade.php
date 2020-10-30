@extends('layouts.app')

@section('content')
<div class="columns is-marginless">
    <div class="column">
        <div class="box">
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <span class="icon is-large is-size-1 text-green">
                            <i class="fas fa-truck-loading"></i>
                        </span>
                    </div>
                </div>
                <div class="level-left">
                    <div class="level-item">
                        <span class="text-green is-size-6 has-text-weight-bold">
                            235 
                            <br>
                            <span class="is-uppercase is-size-7">
                                Products
                            </span>
                            <br><br>
                            <span class="text-green is-size-7 is-uppercase has-text-weight-light">
                                Raw Materials Reorder
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column">
        <div class="box">
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <span class="icon is-large is-size-1 text-purple">
                            <i class="fas fa-check"></i>
                        </span>
                    </div>
                </div>
                <div class="level-left">
                    <div class="level-item">
                        <span class="text-purple is-size-6 has-text-weight-bold">
                            235
                            <br>
                            <span class="is-uppercase is-size-7">
                                Product Types
                            </span>
                            <br><br>
                            <span class="text-purple is-size-7 is-uppercase has-text-weight-light">
                                Available Finished Products
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column">
        <div class="box">
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <span class="icon is-large is-size-1 text-gold">
                            <i class="fas fa-spinner"></i>
                        </span>
                    </div>
                </div>
                <div class="level-left">
                    <div class="level-item">
                        <span class="text-gold is-size-6 has-text-weight-bold">
                            235
                            <br>
                            <span class="is-uppercase is-size-7">
                                Product Types
                            </span>
                            <br><br>
                            <span class="text-gold is-size-7 is-uppercase has-text-weight-light">
                                Products Being Processed
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
