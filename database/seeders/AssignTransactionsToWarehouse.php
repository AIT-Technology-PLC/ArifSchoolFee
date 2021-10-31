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
        DB::transaction(function () {
            // adjustments
            DB::statement('
                UPDATE adjustments
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = adjustments.created_by)
                WHERE warehouse_id IS NULL
            ');

            // damages
            DB::statement('
                UPDATE damages
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = damages.created_by)
                WHERE warehouse_id IS NULL
            ');

            // gdns
            DB::statement('
                UPDATE gdns
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = gdns.created_by)
                WHERE warehouse_id IS NULL
            ');

            // grns
            DB::statement('
                UPDATE grns
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = grns.created_by)
                WHERE warehouse_id IS NULL
            ');

            // proforma_invoices
            DB::statement('
                UPDATE proforma_invoices
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = proforma_invoices.created_by)
                WHERE warehouse_id IS NULL
            ');

            // purchases
            DB::statement('
                UPDATE purchases
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = purchases.created_by)
                WHERE warehouse_id IS NULL
            ');

            // purchase_orders
            DB::statement('
                UPDATE purchase_orders
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = purchase_orders.created_by)
                WHERE warehouse_id IS NULL
            ');

            // reservations
            DB::statement('
                UPDATE reservations
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = reservations.created_by)
                WHERE warehouse_id IS NULL
            ');

            // returns
            DB::statement('
                UPDATE returns
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = returns.created_by)
                WHERE warehouse_id IS NULL
            ');

            // sales
            DB::statement('
                UPDATE sales
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = sales.created_by)
                WHERE warehouse_id IS NULL
            ');

            // sivs
            DB::statement('
                UPDATE sivs
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = sivs.created_by)
                WHERE warehouse_id IS NULL
            ');

            // tenders
            DB::statement('
                UPDATE tenders
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = tenders.created_by)
                WHERE warehouse_id IS NULL
            ');

            // transfers
            DB::statement('
                UPDATE transfers
                SET warehouse_id = (SELECT users.warehouse_id FROM users where users.id = transfers.created_by)
                WHERE warehouse_id IS NULL
            ');
        });
    }
}
