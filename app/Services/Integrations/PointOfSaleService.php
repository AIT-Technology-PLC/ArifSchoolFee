<?php

namespace App\Services\Integrations;

class PointOfSaleService
{
    public function create($sale)
    {
        if (! $sale->warehouse->hasPosIntegration()) {
            return [true, ''];
        }

        $posClass = str($sale->warehouse->pos_provider)->ucfirst()->prepend('App\\Integrations\\PointOfSale\\')->toString();

        return (new $posClass($sale))->create();
    }

    public function getFsNumber($sale)
    {
        if (! $sale->warehouse->hasPosIntegration()) {
            return [true, ''];
        }

        $posClass = str($sale->warehouse->pos_provider)->ucfirst()->prepend('App\\Integrations\\PointOfSale\\')->toString();

        return (new $posClass($sale))->getFsNumber();
    }

    public function isVoid($sale)
    {
        if (! $sale->warehouse->hasPosIntegration()) {
            return [false, ''];
        }

        $posClass = str($sale->warehouse->pos_provider)->ucfirst()->prepend('App\\Integrations\\PointOfSale\\')->toString();

        return (new $posClass($sale))->isVoid();
    }
}
