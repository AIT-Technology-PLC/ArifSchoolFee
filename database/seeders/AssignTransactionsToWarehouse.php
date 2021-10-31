<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignTransactionsToWarehouse extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // adjustments
        DB::statement('
            UPDATE adjustments
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND adjustments.created_by)
            WHERE warehouse_id IS NULL
        ');

        // damages
        DB::statement('
            UPDATE damages
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND damages.created_by)
            WHERE warehouse_id IS NULL
        ');

        // gdns
        DB::statement('
            UPDATE gdns
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND gdns.created_by)
            WHERE warehouse_id IS NULL
        ');

        // grns
        DB::statement('
            UPDATE grns
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND grns.created_by)
            WHERE warehouse_id IS NULL
        ');

        // proforma_invoices
        DB::statement('
            UPDATE proforma_invoices
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND proforma_invoices.created_by)
            WHERE warehouse_id IS NULL
        ');

        // purchases
        DB::statement('
            UPDATE purchases
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND purchases.created_by)
            WHERE warehouse_id IS NULL
        ');

        // purchase_orders
        DB::statement('
            UPDATE purchase_orders
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND purchase_orders.created_by)
            WHERE warehouse_id IS NULL
        ');

        // reservations
        DB::statement('
            UPDATE reservations
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND reservations.created_by)
            WHERE warehouse_id IS NULL
        ');

        // returns
        DB::statement('
            UPDATE returns
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND returns.created_by)
            WHERE warehouse_id IS NULL
        ');

        // sales
        DB::statement('
            UPDATE sales
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND sales.created_by)
            WHERE warehouse_id IS NULL
        ');

        // sivs
        DB::statement('
            UPDATE sivs
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND sivs.created_by)
            WHERE warehouse_id IS NULL
        ');

        // tenders
        DB::statement('
            UPDATE tenders
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND tenders.created_by)
            WHERE warehouse_id IS NULL
        ');

        // transfers
        DB::statement('
            UPDATE transfers
            SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id AND transfers.created_by)
            WHERE warehouse_id IS NULL
        ');
    }
}
