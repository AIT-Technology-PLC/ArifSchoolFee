@extends('layouts.app')

@section('title')
Dashboard - Onrica Inventory
@endsection

@section('content')
<div class="columns is-marginless">
    <div class="column is-3">
        <div class="box text-green">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-truck-loading"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        59
                    </div>
                    <div class="is-uppercase is-size-7">
                        Material Types
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="is-size-7 is-uppercase has-text-grey">
                Raw Materials Out of Stock
            </div>
        </div>
    </div>
    <div class="column is-3">
        <div class="box text-purple">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-boxes"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        13
                    </div>
                    <div class="is-uppercase is-size-7">
                        Product Types
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="is-size-7 is-uppercase has-text-grey">
                Finished Products Available
            </div>
        </div>
    </div>
    <div class="column is-3">
        <div class="box text-gold">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-sync-alt"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        27
                    </div>
                    <div class="is-uppercase is-size-7">
                        Product Types
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="is-size-7 is-uppercase has-text-grey">
                Products In Process
            </div>
        </div>
    </div>
    <div class="column is-3">
        <div class="box text-blue">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-tools"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        27
                    </div>
                    <div class="is-uppercase is-size-7">
                        Items
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="is-size-7 is-uppercase has-text-grey">
                MRO Items Available
            </div>
        </div>
    </div>
</div>

<div class="columns is-marginless is-vcentered">
    <div class="column is-6">
        <div class="box mb-0 radius-bottom-0 pb-1">
            <p class="has-text-black has-text-weight-medium">
                Stock Level (%)
            </p>
            <p class="is-size-7 has-text-grey mb-4">
                Manufacturing Inventory
            </p>
        </div>
        <div class="box radius-top-0">
            {!! $chartjs->render() !!}
        </div>
    </div>
    <div class="column is-3">
        <div class="box mb-0 radius-bottom-0 pb-1">
            <p class="has-text-black has-text-weight-medium">
                Finished Products (%)
            </p>
            <p class="is-size-7 has-text-grey mb-4">
                Manufacturing Inventory - Top 5
            </p>
        </div>
        <div class="box radius-top-0">
            {!! $pie2->render() !!}
        </div>
    </div>
    <div class="column is-3">
        <div class="box mb-0 radius-bottom-0 pb-1">
            <p class="has-text-black has-text-weight-medium">
                Raw Materials (%)
            </p>
            <p class="is-size-7 has-text-grey mb-4">
                Manufacturing Inventory - Top 5
            </p>
        </div>
        <div class="box radius-top-0">
            {!! $pie->render() !!}
        </div>
    </div>
</div>

<div class="columns is-marginless">
    <div class="column is-3">
        <div class="box text-green">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-check"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        59
                    </div>
                    <div class="is-uppercase is-size-7">
                        Products
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="is-size-7 is-uppercase has-text-grey">
                Available In Stock
            </div>
        </div>
    </div>
    <div class="column is-3">
        <div class="box text-purple">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-times"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        13
                    </div>
                    <div class="is-uppercase is-size-7">
                        Products
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="is-size-7 is-uppercase has-text-grey">
                Out Of Stock
            </div>
        </div>
    </div>
    <div class="column is-3">
        <div class="box text-gold">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-exclamation"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        27
                    </div>
                    <div class="is-uppercase is-size-7">
                        Product Types
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="is-size-7 is-uppercase has-text-grey">
                Limited Stock
            </div>
        </div>
    </div>
    <div class="column is-3">
        <div class="box text-blue">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-undo-alt"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        27
                    </div>
                    <div class="is-uppercase is-size-7">
                        Products
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="is-size-7 is-uppercase has-text-grey">
                Returned By Clients
            </div>
        </div>
    </div>
</div>

<div class="columns is-marginless is-vcentered">
    <div class="column is-6">
        <div class="box mb-0 radius-bottom-0 pb-1">
            <p class="has-text-black has-text-weight-medium">
                Stock Level (%)
            </p>
            <p class="is-size-7 has-text-grey mb-4">
                Merchandise Inventory
            </p>
        </div>
        <div class="box radius-top-0">
            {!! $chartjsMerchandise->render() !!}
        </div>
    </div>
    <div class="column is-3">
        <div class="box mb-0 radius-bottom-0 pb-1">
            <p class="has-text-black has-text-weight-medium">
                Products By Category (%)
            </p>
            <p class="is-size-7 has-text-grey mb-4">
                Merchandise Inventory
            </p>
        </div>
        <div class="box radius-top-0">
            {!! $pieMerchandise->render() !!}
        </div>
    </div>
    <div class="column is-3">
        <div class="box mb-0 radius-bottom-0 pb-1">
            <p class="has-text-black has-text-weight-medium">
                Sold Products (%)
            </p>
            <p class="is-size-7 has-text-grey mb-4">
                Merchandise Inventory
            </p>
        </div>
        <div class="box radius-top-0">
            {!! $pieMerchandiseSales->render() !!}
        </div>
    </div>
</div>

<div class="columns is-marginless is-vcentered">
    <div class="column">
        <div class="box mb-0 radius-bottom-0 pb-1">
            <p class="has-text-black has-text-weight-medium">
                Finished Products
            </p>
            <p class="is-size-7 has-text-grey mb-4">
                Manufacturing Inventory
            </p>
        </div>
        <div class="box radius-top-0">
            <table class="table is-hoverable is-fullwidth is-size-7">
                <thead>
                    <tr>
                        <th><abbr title=""> # </abbr></th>
                        <th><abbr title=""> Name </abbr></th>
                        <th><abbr title=""> On Hand </abbr></th>
                        <th><abbr title=""> Sold </abbr></th>
                        <th><abbr title=""> Returns </abbr></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th> 1 </th>
                        <td> Harari Coffee </td>
                        <td> 100 KG </td>
                        <td> <span class="tag is-small bg-blue has-text-white"> 30 KG </span> </td>
                        <td> <span class="tag is-small bg-purple has-text-white"> 10 KG </span> </td>
                    </tr>
                    <tr>
                        <th> 2 </th>
                        <td> Harari Coffee </td>
                        <td> 100 KG </td>
                        <td> <span class="tag is-small bg-blue has-text-white"> 30 KG </span> </td>
                        <td> <span class="tag is-small bg-purple has-text-white"> 10 KG </span> </td>
                    </tr>
                    <tr>
                        <th> 3 </th>
                        <td> Harari Coffee </td>
                        <td> 100 KG </td>
                        <td> <span class="tag is-small bg-blue has-text-white"> 30 KG </span> </td>
                        <td> <span class="tag is-small bg-purple has-text-white"> 10 KG </span> </td>
                    </tr>
                    <tr>
                        <th> 4 </th>
                        <td> Harari Coffee </td>
                        <td> 100 KG </td>
                        <td> <span class="tag is-small bg-blue has-text-white"> 30 KG </span> </td>
                        <td> <span class="tag is-small bg-purple has-text-white"> 10 KG </span> </td>
                    </tr>
                    <tr>
                        <th> 5 </th>
                        <td> Harari Coffee </td>
                        <td> 100 KG </td>
                        <td> <span class="tag is-small bg-blue has-text-white"> 30 KG </span> </td>
                        <td> <span class="tag is-small bg-purple has-text-white"> 10 KG </span> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="column">
        <div class="box mb-0 radius-bottom-0 pb-1">
            <p class="has-text-black has-text-weight-medium">
                Products In Process
            </p>
            <p class="is-size-7 has-text-grey mb-4">
                Manufacturing Inventory
            </p>
        </div>
        <div class="box radius-top-0">
            <table class="table is-hoverable is-fullwidth is-size-7">
                <thead>
                    <tr>
                        <th><abbr title=""> # </abbr></th>
                        <th><abbr title=""> Name </abbr></th>
                        <th><abbr title=""> In Process </abbr></th>
                        <th><abbr title=""> Finished </abbr></th>
                        <th><abbr title=""> Status </abbr></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th> 1 </th>
                        <td> Harari Coffee </td>
                        <td> 100 KG </td>
                        <td> <span class="tag is-small bg-green has-text-white"> 30 KG </span> </td>
                        <td> <span class="tag is-small bg-gold has-text-white"> In Process </span> </td>
                    </tr>
                    <tr>
                        <th> 2 </th>
                        <td> Harari Coffee </td>
                        <td> 100 KG </td>
                        <td> <span class="tag is-small bg-green has-text-white"> 30 KG </span> </td>
                        <td> <span class="tag is-small bg-gold has-text-white"> In Process </span> </td>
                    </tr>
                    <tr>
                        <th> 3 </th>
                        <td> Harari Coffee </td>
                        <td> 100 KG </td>
                        <td> <span class="tag is-small bg-green has-text-white"> 30 KG </span> </td>
                        <td> <span class="tag is-small bg-gold has-text-white"> In Process </span> </td>
                    </tr>
                    <tr>
                        <th> 4 </th>
                        <td> Harari Coffee </td>
                        <td> 100 KG </td>
                        <td> <span class="tag is-small bg-green has-text-white"> 30 KG </span> </td>
                        <td> <span class="tag is-small bg-gold has-text-white"> In Process </span> </td>
                    </tr>
                    <tr>
                        <th> 5 </th>
                        <td> Harari Coffee </td>
                        <td> 100 KG </td>
                        <td> <span class="tag is-small bg-green has-text-white"> 30 KG </span> </td>
                        <td> <span class="tag is-small bg-gold has-text-white"> In Process </span> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
