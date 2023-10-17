<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceSalesReportViews extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        turnOffPreparedStatementEmulation();

        turnOffMysqlStictMode();

        $this->salesTableView();

        $this->gdnsTableView();
    }

    private function salesTableView()
    {
        DB::unprepared(
            "
            CREATE OR REPLACE VIEW sale_detail_reports AS SELECT
                sale_details.sale_id,
                sale_details.sale_id AS master_id,
                sale_details.sale_id AS sale_master_report_id,
                product_categories.id AS product_category_id,
                product_categories.name AS product_category_name,
                sale_details.product_id,
                products.name AS product_name,
                products.code AS product_code,
                products.unit_of_measurement AS product_unit_of_measurement,
                sale_details.quantity,
                sale_details.unit_price,
                IF(
                    products.is_product_single=1,
                    (SELECT inventory_valuation_histories.unit_cost FROM inventory_valuation_histories WHERE inventory_valuation_histories.product_id = sale_details.product_id AND inventory_valuation_histories.type = products.inventory_valuation_method AND inventory_valuation_histories.created_at <= sales.issued_on AND inventory_valuation_histories.deleted_at is NULL ORDER BY inventory_valuation_histories.id DESC LIMIT 1),
                    (SELECT SUM(inventory_valuation_histories.unit_cost*product_bundles.quantity) FROM product_bundles INNER JOIN inventory_valuation_histories ON product_bundles.component_id = inventory_valuation_histories.product_id WHERE product_bundles.product_id = sale_details.product_id AND inventory_valuation_histories.type = products.inventory_valuation_method AND inventory_valuation_histories.created_at <= sales.issued_on AND inventory_valuation_histories.deleted_at is NULL GROUP BY product_bundles.product_id ORDER BY inventory_valuation_histories.id DESC)
                ) AS unit_cost,
                brands.name AS brand_name,
                IF(companies.is_price_before_vat=1, sale_details.unit_price, sale_details.unit_price/(1+taxes.amount))*sale_details.quantity AS line_price_before_tax,
                IF(companies.is_price_before_vat=1, sale_details.unit_price, sale_details.unit_price/(1+taxes.amount))*sale_details.quantity*taxes.amount AS line_tax
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
            INNER JOIN taxes
                ON products.tax_id = taxes.id
            LEFT JOIN brands
                ON products.brand_id = brands.id
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
                sales.warehouse_id,
                sales.warehouse_id AS branch_id,
                warehouses.name AS branch_name,
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
                (SELECT SUM(sale_detail_reports.line_price_before_tax) FROM sale_detail_reports WHERE sale_detail_reports.sale_id = sales.id) AS subtotal_price,
                (SELECT SUM(sale_detail_reports.line_tax) FROM sale_detail_reports WHERE sale_detail_reports.sale_id = sales.id) AS total_tax,
                credits.credit_amount,
                credits.credit_amount_settled,
                (credits.credit_amount - credits.credit_amount_settled) AS credit_amount_unsettled,
                credits.last_settled_at
            FROM
                sales
            INNER JOIN warehouses
                ON sales.warehouse_id = warehouses.id AND warehouses.is_active = 1
            LEFT JOIN users
                ON sales.created_by = users.id
            LEFT JOIN customers
                ON sales.customer_id = customers.id
            LEFT JOIN credits
                ON sales.id = credits.creditable_id AND credits.creditable_type = 'App\\\\Models\\\\Sale'
            INNER JOIN companies
                ON sales.company_id = companies.id
            WHERE
                sales.deleted_at IS NULL AND sales.cancelled_by IS NULL AND IF(companies.can_sale_subtract = 1, sales.subtracted_by IS NOT NULL, sales.approved_by IS NOT NULL)
            "
        );
    }

    private function gdnsTableView()
    {
        DB::unprepared(
            "
            CREATE OR REPLACE VIEW gdn_detail_reports AS SELECT
                gdn_details.gdn_id,
                gdn_details.gdn_id AS master_id,
                gdn_details.gdn_id AS gdn_master_report_id,
                product_categories.id AS product_category_id,
                product_categories.name AS product_category_name,
                gdn_details.product_id,
                products.name AS product_name,
                products.code AS product_code,
                products.unit_of_measurement AS product_unit_of_measurement,
                gdn_details.warehouse_id,
                warehouses.name AS warehouse_name,
                gdn_details.quantity,
                gdn_details.unit_price,
                IF(
                    products.is_product_single=1,
                    (SELECT inventory_valuation_histories.unit_cost FROM inventory_valuation_histories WHERE inventory_valuation_histories.product_id = gdn_details.product_id AND inventory_valuation_histories.type = products.inventory_valuation_method AND inventory_valuation_histories.created_at <= gdns.issued_on AND inventory_valuation_histories.deleted_at is NULL ORDER BY inventory_valuation_histories.id DESC LIMIT 1),
                    (SELECT SUM(inventory_valuation_histories.unit_cost*product_bundles.quantity) FROM product_bundles INNER JOIN inventory_valuation_histories ON product_bundles.component_id = inventory_valuation_histories.product_id WHERE product_bundles.product_id = gdn_details.product_id AND inventory_valuation_histories.type = products.inventory_valuation_method AND inventory_valuation_histories.created_at <= gdns.issued_on AND inventory_valuation_histories.deleted_at is NULL GROUP BY product_bundles.product_id ORDER BY inventory_valuation_histories.id DESC)
                ) AS unit_cost,
                gdn_details.discount,
                brands.name AS brand_name,
                IF(
                    gdn_details.discount IS NULL,
                    IF(companies.is_price_before_vat=1, gdn_details.unit_price, gdn_details.unit_price/(1+taxes.amount))*gdn_details.quantity,
                    IF(companies.is_price_before_vat=1, gdn_details.unit_price, gdn_details.unit_price/(1+taxes.amount))*gdn_details.quantity - IF(companies.is_price_before_vat=1, gdn_details.unit_price, gdn_details.unit_price/(1+taxes.amount))*gdn_details.quantity * (gdn_details.discount / 100)
                ) AS line_price_before_tax,
                IF(
                    gdn_details.discount IS NULL,
                    IF(companies.is_price_before_vat=1, gdn_details.unit_price, gdn_details.unit_price/(1+taxes.amount))*gdn_details.quantity*(taxes.amount),
                    IF(companies.is_price_before_vat=1, gdn_details.unit_price, gdn_details.unit_price/(1+taxes.amount))*gdn_details.quantity*(taxes.amount) - IF(companies.is_price_before_vat=1, gdn_details.unit_price, gdn_details.unit_price/(1+taxes.amount))*gdn_details.quantity*(taxes.amount) * (gdn_details.discount / 100)
                ) AS line_tax
            FROM
                gdn_details
            INNER JOIN gdns
                    ON gdns.id = gdn_details.gdn_id AND gdns.deleted_at IS NULL AND gdns.cancelled_by IS NULL
            INNER JOIN companies
                    ON gdns.company_id = companies.id
            INNER JOIN products
                ON gdn_details.product_id = products.id
            INNER JOIN product_categories
                ON products.product_category_id = product_categories.id
            INNER JOIN taxes
                ON products.tax_id = taxes.id
            INNER JOIN warehouses
                ON gdn_details.warehouse_id = warehouses.id AND warehouses.is_active = 1
            LEFT JOIN brands
                ON products.brand_id = brands.id
            WHERE
                gdn_details.deleted_at IS NULL
            "
        );

        DB::unprepared(
            "
            CREATE OR REPLACE VIEW gdn_master_reports AS SELECT
                gdns.id,
                gdns.company_id,
                gdns.created_by,
                users.name AS user_name,
                gdns.warehouse_id,
                gdns.warehouse_id AS branch_id,
                warehouses.name AS branch_name,
                gdns.code,
                gdns.customer_id,
                customers.company_name AS customer_name,
                customers.address AS customer_address,
                (SELECT MIN(gdns_two.issued_on) FROM gdns gdns_two WHERE gdns_two.customer_id = gdns.customer_id AND gdns_two.deleted_at IS NULL) AS customer_created_at,
                gdns.payment_type,
                gdns.cash_received_type,
                gdns.cash_received,
                gdns.discount,
                gdns.issued_on,
                IF(
                    gdns.discount IS NULL,
                    (SELECT SUM(gdn_detail_reports.line_price_before_tax) FROM gdn_detail_reports WHERE gdn_detail_reports.gdn_id = gdns.id),
                    (SELECT SUM(gdn_detail_reports.line_price_before_tax) FROM gdn_detail_reports WHERE gdn_detail_reports.gdn_id = gdns.id) - (SELECT SUM(gdn_detail_reports.line_price_before_tax) FROM gdn_detail_reports WHERE gdn_detail_reports.gdn_id = gdns.id) * (gdns.discount / 100)
                ) AS subtotal_price,
                IF(
                    gdns.discount IS NULL,
                    (SELECT SUM(gdn_detail_reports.line_tax) FROM gdn_detail_reports WHERE gdn_detail_reports.gdn_id = gdns.id),
                    (SELECT SUM(gdn_detail_reports.line_tax) FROM gdn_detail_reports WHERE gdn_detail_reports.gdn_id = gdns.id) - (SELECT SUM(gdn_detail_reports.line_tax) FROM gdn_detail_reports WHERE gdn_detail_reports.gdn_id = gdns.id) * (gdns.discount / 100)
                ) AS total_tax,
                credits.credit_amount,
                credits.credit_amount_settled,
                (credits.credit_amount - credits.credit_amount_settled) AS credit_amount_unsettled,
                credits.last_settled_at
            FROM
                gdns
            INNER JOIN warehouses
                ON gdns.warehouse_id = warehouses.id AND warehouses.is_active = 1
            LEFT JOIN users
                ON gdns.created_by = users.id
            LEFT JOIN customers
                ON gdns.customer_id = customers.id
            LEFT JOIN credits
                ON gdns.id = credits.creditable_id AND credits.creditable_type = 'App\\\\Models\\\\Gdn'
            INNER JOIN companies
                ON gdns.company_id = companies.id
            WHERE
                gdns.deleted_at IS NULL AND gdns.cancelled_by IS NULL AND gdns.subtracted_by IS NOT NULL
            "
        );
    }
}
