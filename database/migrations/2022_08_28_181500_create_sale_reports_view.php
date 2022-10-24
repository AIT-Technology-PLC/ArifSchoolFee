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
        turnOffPreparedStatementEmulation();
        turnOffMysqlStictMode();

        DB::unprepared(
            "
            CREATE OR REPLACE VIEW sale_detail_reports AS SELECT
                sale_details.sale_id,
                sale_details.sale_id AS sale_master_report_id,
                product_categories.id AS product_category_id,
                product_categories.name AS product_category_name,
                sale_details.product_id,
                products.name AS product_name,
                products.unit_of_measurement AS product_unit_of_measurement,
                sale_details.quantity,
                sale_details.unit_price,
                (SELECT ROUND(SUM(IF(companies.is_price_before_vat=1, ROUND(sd.unit_price, 2), ROUND(sd.unit_price/1.15, 2))*sd.quantity), 2) FROM sale_details sd WHERE sd.id = sale_details.id) AS line_price
            FROM
                sale_details
            INNER JOIN sales
                    ON sales.id = sale_details.sale_id AND sales.deleted_at IS NULL AND sales.cancelled_by IS NULL
            INNER JOIN companies
                    ON sales.company_id = companies.id
            INNER JOIN products
                ON sale_details.product_id = products.id
            INNER JOIN product_categories
                ON products.product_category_id = product_categories.id
            WHERE
                sale_details.deleted_at IS NULL
            "
        );

        DB::unprepared(
            "
            CREATE OR REPLACE VIEW sale_master_reports AS SELECT
                sales.id,
                sales.company_id,
                sales.created_by,
                users.name AS user_name,
                CASE
                    WHEN sales.approved_by IS NOT NULL THEN 'approved'
                    ELSE 'not_approved'
                END AS status,
                sales.warehouse_id,
                warehouses.name AS warehouse_name,
                sales.code,
                sales.customer_id,
                customers.company_name AS customer_name,
                customers.address AS customer_address,
                (SELECT MIN(sales_two.issued_on) FROM sales sales_two WHERE sales_two.customer_id = sales.customer_id AND sales_two.deleted_at IS NULL AND sales_two.cancelled_by IS NULL) AS customer_created_at,
                sales.fs_number,
                sales.payment_type,
                sales.cash_received_type,
                sales.cash_received,
                sales.issued_on,
                (SELECT ROUND(SUM(sale_detail_reports.line_price), 2) FROM sale_detail_reports WHERE sale_detail_reports.sale_id = sales.id) AS subtotal_price
            FROM
                sales
            INNER JOIN warehouses
                ON sales.warehouse_id = warehouses.id AND warehouses.is_active = 1
            LEFT JOIN users
                ON sales.created_by = users.id
            LEFT JOIN customers
                ON sales.customer_id = customers.id
            INNER JOIN companies
                ON sales.company_id = companies.id
            WHERE
                sales.deleted_at IS NULL AND sales.cancelled_by IS NULL
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
        turnOffPreparedStatementEmulation();
        turnOffMysqlStictMode();

        DB::unprepared('DROP VIEW sale_master_reports');
        DB::unprepared('DROP VIEW sale_detail_reports');
    }
};
