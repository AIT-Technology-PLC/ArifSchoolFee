<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "
            CREATE OR REPLACE VIEW gdn_detail_reports AS SELECT
                gdn_details.gdn_id,
                gdn_details.gdn_id AS gdn_master_report_id,
                product_categories.id AS product_category_id,
                product_categories.name AS product_category_name,
                gdn_details.product_id,
                products.name AS product_name,
                products.unit_of_measurement AS product_unit_of_measurement,
                gdn_details.warehouse_id,
                warehouses.name AS warehouse_name,
                gdn_details.quantity,
                gdn_details.unit_price,
                gdn_details.discount,
                IF(
                    gdn_details.discount IS NULL,
                    (SELECT ROUND(SUM(IF(companies.is_price_before_vat=1, ROUND(gd.unit_price, 2), ROUND(gd.unit_price/1.15, 2))*gd.quantity), 2) FROM gdn_details gd WHERE gd.id = gdn_details.id),
                    (SELECT ROUND(SUM(IF(companies.is_price_before_vat=1, ROUND(gd.unit_price, 2), ROUND(gd.unit_price/1.15, 2))*gd.quantity), 2) FROM gdn_details gd WHERE gd.id = gdn_details.id) - ROUND((SELECT ROUND(SUM(IF(companies.is_price_before_vat=1, ROUND(gd.unit_price, 2), ROUND(gd.unit_price/1.15, 2))*gd.quantity), 2) FROM gdn_details gd WHERE gd.id = gdn_details.id) * gdn_details.discount, 2)
                ) AS line_price
            FROM
                gdn_details
            INNER JOIN gdns
                    ON gdns.id = gdn_details.gdn_id AND gdns.deleted_at IS NULL
            INNER JOIN companies
                    ON gdns.company_id = companies.id
            INNER JOIN products
                ON gdn_details.product_id = products.id
            INNER JOIN product_categories
                ON products.product_category_id = product_categories.id
            INNER JOIN warehouses
                ON gdn_details.warehouse_id = warehouses.id AND warehouses.is_active = 1 
            WHERE
                gdn_details.deleted_at IS NULL
            "
        );

        DB::statement(
            "
            CREATE OR REPLACE VIEW gdn_master_reports AS SELECT
                gdns.id,
                gdns.company_id,
                gdns.created_by,
                users.name AS user_name,
                CASE
                    WHEN gdns.subtracted_by IS NOT NULL THEN 'subtracted'
                    WHEN gdns.approved_by IS NOT NULL THEN 'approved'
                    ELSE 'not_approved'
                END AS status,
                gdns.warehouse_id,
                warehouses.name AS warehouse_name,
                gdns.code,
                gdns.customer_id,
                customers.company_name AS customer_name,
                customers.address AS customer_address,
                gdns.payment_type,
                gdns.cash_received_type,
                gdns.cash_received,
                gdns.discount,
                gdns.issued_on,
                IF(
                    gdns.discount IS NULL,
                    (SELECT ROUND(SUM(gdn_detail_reports.line_price), 2) FROM gdn_detail_reports WHERE gdn_detail_reports.gdn_id = gdns.id),
                    (SELECT ROUND(SUM(gdn_detail_reports.line_price), 2) FROM gdn_detail_reports WHERE gdn_detail_reports.gdn_id = gdns.id) - ROUND((SELECT ROUND(SUM(gdn_detail_reports.line_price), 2) FROM gdn_detail_reports WHERE gdn_detail_reports.gdn_id = gdns.id) * gdns.discount, 2)
                ) AS subtotal_price
            FROM
                gdns
            INNER JOIN warehouses
                ON gdns.warehouse_id = warehouses.id AND warehouses.is_active = 1
            LEFT JOIN users
                ON gdns.created_by = users.id
            LEFT JOIN customers
                ON gdns.customer_id = customers.id
            INNER JOIN companies
                ON gdns.company_id = companies.id
            WHERE
                gdns.deleted_at IS NULL
            "
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW gdn_master_reports');
        DB::statement('DROP VIEW gdn_detail_reports');
    }
};
