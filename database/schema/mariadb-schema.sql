/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `adjustment_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adjustment_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `adjustment_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `merchandise_batch_id` bigint(20) unsigned DEFAULT NULL,
  `is_subtract` tinyint(1) NOT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `reason` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `adjustment_details_adjustment_id_index` (`adjustment_id`),
  KEY `adjustment_details_warehouse_id_foreign` (`warehouse_id`),
  KEY `adjustment_details_product_id_foreign` (`product_id`),
  KEY `adjustment_details_merchandise_batch_id_foreign` (`merchandise_batch_id`),
  CONSTRAINT `adjustment_details_adjustment_id_foreign` FOREIGN KEY (`adjustment_id`) REFERENCES `adjustments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `adjustment_details_merchandise_batch_id_foreign` FOREIGN KEY (`merchandise_batch_id`) REFERENCES `merchandise_batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `adjustment_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `adjustment_details_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adjustments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `adjusted_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `description` longtext DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `adjustments_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `adjustments_company_id_index` (`company_id`),
  KEY `adjustments_created_by_foreign` (`created_by`),
  KEY `adjustments_updated_by_foreign` (`updated_by`),
  KEY `adjustments_approved_by_foreign` (`approved_by`),
  KEY `adjustments_adjusted_by_foreign` (`adjusted_by`),
  KEY `adjustments_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `adjustments_adjusted_by_foreign` FOREIGN KEY (`adjusted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `adjustments_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `adjustments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `adjustments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `adjustments_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `adjustments_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `advancement_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `advancement_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `advancement_id` bigint(20) unsigned DEFAULT NULL,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `compensation_id` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL,
  `job_position` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `advancement_details_employee_id_foreign` (`employee_id`),
  KEY `advancement_details_advancement_id_index` (`advancement_id`),
  KEY `advancement_details_compensation_id_foreign` (`compensation_id`),
  CONSTRAINT `advancement_details_advancement_id_foreign` FOREIGN KEY (`advancement_id`) REFERENCES `advancements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `advancement_details_compensation_id_foreign` FOREIGN KEY (`compensation_id`) REFERENCES `compensations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `advancement_details_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `advancements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `advancements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `issued_on` datetime DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `advancements_created_by_foreign` (`created_by`),
  KEY `advancements_updated_by_foreign` (`updated_by`),
  KEY `advancements_approved_by_foreign` (`approved_by`),
  KEY `advancements_company_id_index` (`company_id`),
  KEY `advancements_warehouse_id_index` (`warehouse_id`),
  CONSTRAINT `advancements_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `advancements_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `advancements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `advancements_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `advancements_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `announcement_warehouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcement_warehouse` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `announcement_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcement_warehouse_announcement_id_index` (`announcement_id`),
  KEY `announcement_warehouse_warehouse_id_index` (`warehouse_id`),
  CONSTRAINT `announcement_warehouse_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `announcement_warehouse_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_created_by_foreign` (`created_by`),
  KEY `announcements_updated_by_foreign` (`updated_by`),
  KEY `announcements_approved_by_foreign` (`approved_by`),
  KEY `announcements_company_id_index` (`company_id`),
  CONSTRAINT `announcements_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `announcements_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `announcements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `announcements_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `attendance_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attendance_id` bigint(20) unsigned DEFAULT NULL,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `days` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendance_details_employee_id_foreign` (`employee_id`),
  KEY `attendance_details_attendance_id_index` (`attendance_id`),
  CONSTRAINT `attendance_details_attendance_id_foreign` FOREIGN KEY (`attendance_id`) REFERENCES `attendances` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attendance_details_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `cancelled_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `issued_on` datetime DEFAULT NULL,
  `starting_period` date DEFAULT NULL,
  `ending_period` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_created_by_foreign` (`created_by`),
  KEY `attendances_updated_by_foreign` (`updated_by`),
  KEY `attendances_approved_by_foreign` (`approved_by`),
  KEY `attendances_cancelled_by_foreign` (`cancelled_by`),
  KEY `attendances_company_id_index` (`company_id`),
  KEY `attendances_warehouse_id_index` (`warehouse_id`),
  CONSTRAINT `attendances_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `attendances_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `attendances_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attendances_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `attendances_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `attendances_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bill_of_material_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill_of_material_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bill_of_material_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bill_of_material_details_bill_of_material_id_index` (`bill_of_material_id`),
  KEY `bill_of_material_details_product_id_index` (`product_id`),
  CONSTRAINT `bill_of_material_details_bill_of_material_id_foreign` FOREIGN KEY (`bill_of_material_id`) REFERENCES `bill_of_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bill_of_material_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bill_of_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill_of_materials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bill_of_materials_created_by_foreign` (`created_by`),
  KEY `bill_of_materials_updated_by_foreign` (`updated_by`),
  KEY `bill_of_materials_company_id_index` (`company_id`),
  KEY `bill_of_materials_product_id_index` (`product_id`),
  KEY `bill_of_materials_customer_id_foreign` (`customer_id`),
  KEY `bill_of_materials_approved_by_foreign` (`approved_by`),
  CONSTRAINT `bill_of_materials_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bill_of_materials_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bill_of_materials_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bill_of_materials_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bill_of_materials_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bill_of_materials_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_company_id_name_unique` (`company_id`,`name`),
  KEY `brands_created_by_foreign` (`created_by`),
  KEY `brands_updated_by_foreign` (`updated_by`),
  KEY `brands_company_id_index` (`company_id`),
  CONSTRAINT `brands_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `brands_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `brands_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `plan_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `sector` varchar(255) DEFAULT NULL,
  `sales_report_source` varchar(255) NOT NULL DEFAULT 'Delivery Orders',
  `enabled` tinyint(1) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `tin` varchar(255) DEFAULT NULL,
  `proforma_invoice_prefix` varchar(255) DEFAULT NULL,
  `is_price_before_vat` tinyint(1) NOT NULL DEFAULT 1,
  `is_discount_before_vat` tinyint(1) NOT NULL DEFAULT 1,
  `is_convert_to_siv_as_approved` tinyint(1) NOT NULL DEFAULT 1,
  `is_editing_reference_number_enabled` varchar(255) NOT NULL DEFAULT '1',
  `can_show_branch_detail_on_print` tinyint(1) NOT NULL DEFAULT 0,
  `paid_time_off_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `paid_time_off_type` varchar(255) NOT NULL DEFAULT 'Days',
  `working_days` bigint(20) NOT NULL DEFAULT 26,
  `is_backorder_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `can_check_inventory_on_forms` tinyint(1) NOT NULL DEFAULT 0,
  `expiry_in_days` int(11) DEFAULT 30,
  `can_show_employee_job_title_on_print` tinyint(1) NOT NULL DEFAULT 1,
  `can_select_batch_number_on_forms` tinyint(1) NOT NULL DEFAULT 0,
  `filter_customer_and_supplier` tinyint(1) NOT NULL DEFAULT 1,
  `is_costing_by_freight_volume` tinyint(1) NOT NULL DEFAULT 1,
  `income_tax_region` varchar(255) NOT NULL DEFAULT 'Ethiopia',
  `payroll_bank_name` varchar(255) DEFAULT NULL,
  `payroll_bank_account_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `print_template_image` varchar(255) DEFAULT NULL,
  `print_padding_top` decimal(8,2) NOT NULL DEFAULT 10.00,
  `print_padding_bottom` decimal(8,2) NOT NULL DEFAULT 10.00,
  `print_padding_horizontal` decimal(8,2) NOT NULL DEFAULT 5.00,
  `is_payroll_basic_salary_after_absence_deduction` tinyint(1) NOT NULL DEFAULT 0,
  `does_payroll_basic_salary_include_overtime` tinyint(1) NOT NULL DEFAULT 0,
  `is_return_limited_by_sales` varchar(255) NOT NULL DEFAULT '0',
  `can_sale_subtract` tinyint(1) NOT NULL DEFAULT 0,
  `can_sell_below_cost` tinyint(1) NOT NULL DEFAULT 1,
  `can_siv_subtract_from_inventory` tinyint(1) NOT NULL DEFAULT 0,
  `is_partial_deliveries_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `auto_generated_credit_issued_on_date` varchar(255) NOT NULL DEFAULT 'approval_date',
  `show_product_code_on_printouts` tinyint(1) NOT NULL DEFAULT 1,
  `is_in_training` tinyint(1) NOT NULL DEFAULT 0,
  `subscription_expires_on` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_tin_unique` (`tin`),
  KEY `companies_plan_id_index` (`plan_id`),
  CONSTRAINT `companies_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `company_integration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_integration` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `integration_id` bigint(20) unsigned NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_integration_company_id_foreign` (`company_id`),
  KEY `company_integration_integration_id_foreign` (`integration_id`),
  CONSTRAINT `company_integration_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `company_integration_integration_id_foreign` FOREIGN KEY (`integration_id`) REFERENCES `integrations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `compensation_adjustment_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compensation_adjustment_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `adjustment_id` bigint(20) unsigned DEFAULT NULL,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `compensation_id` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compensation_adjustment_details_employee_id_foreign` (`employee_id`),
  KEY `compensation_adjustment_details_compensation_id_foreign` (`compensation_id`),
  KEY `compensation_adjustment_details_adjustment_id_index` (`adjustment_id`),
  CONSTRAINT `compensation_adjustment_details_adjustment_id_foreign` FOREIGN KEY (`adjustment_id`) REFERENCES `compensation_adjustments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compensation_adjustment_details_compensation_id_foreign` FOREIGN KEY (`compensation_id`) REFERENCES `compensations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compensation_adjustment_details_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `compensation_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compensation_adjustments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `cancelled_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `issued_on` datetime DEFAULT NULL,
  `starting_period` date DEFAULT NULL,
  `ending_period` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compensation_adjustments_created_by_foreign` (`created_by`),
  KEY `compensation_adjustments_updated_by_foreign` (`updated_by`),
  KEY `compensation_adjustments_approved_by_foreign` (`approved_by`),
  KEY `compensation_adjustments_cancelled_by_foreign` (`cancelled_by`),
  KEY `compensation_adjustments_company_id_index` (`company_id`),
  CONSTRAINT `compensation_adjustments_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `compensation_adjustments_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `compensation_adjustments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compensation_adjustments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `compensation_adjustments_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `compensations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compensations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `depends_on` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_taxable` tinyint(1) NOT NULL,
  `is_adjustable` tinyint(1) NOT NULL,
  `can_be_inputted_manually` tinyint(1) NOT NULL,
  `percentage` decimal(22,2) DEFAULT NULL,
  `default_value` decimal(22,2) DEFAULT NULL,
  `maximum_amount` decimal(22,2) DEFAULT NULL,
  `has_formula` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compensations_created_by_foreign` (`created_by`),
  KEY `compensations_updated_by_foreign` (`updated_by`),
  KEY `compensations_company_id_index` (`company_id`),
  KEY `compensations_depends_on_foreign` (`depends_on`),
  CONSTRAINT `compensations_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compensations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `compensations_depends_on_foreign` FOREIGN KEY (`depends_on`) REFERENCES `compensations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compensations_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `tin` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contacts_company_id_tin_unique` (`company_id`,`tin`),
  KEY `contacts_created_by_foreign` (`created_by`),
  KEY `contacts_updated_by_foreign` (`updated_by`),
  KEY `contacts_company_id_index` (`company_id`),
  CONSTRAINT `contacts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `contacts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `contacts_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cost_update_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cost_update_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cost_update_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `lifo_unit_cost` decimal(30,10) DEFAULT NULL,
  `fifo_unit_cost` decimal(30,10) DEFAULT NULL,
  `average_unit_cost` decimal(30,10) NOT NULL DEFAULT 0.0000000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cost_update_details_cost_update_id_index` (`cost_update_id`),
  KEY `cost_update_details_product_id_index` (`product_id`),
  CONSTRAINT `cost_update_details_cost_update_id_foreign` FOREIGN KEY (`cost_update_id`) REFERENCES `cost_updates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cost_update_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cost_updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cost_updates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `rejected_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cost_updates_created_by_foreign` (`created_by`),
  KEY `cost_updates_updated_by_foreign` (`updated_by`),
  KEY `cost_updates_rejected_by_foreign` (`rejected_by`),
  KEY `cost_updates_approved_by_foreign` (`approved_by`),
  KEY `cost_updates_company_id_index` (`company_id`),
  CONSTRAINT `cost_updates_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cost_updates_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cost_updates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cost_updates_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cost_updates_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `credit_settlements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_settlements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `credit_id` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL,
  `method` varchar(255) NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `settled_at` datetime DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `credit_settlements_credit_id_index` (`credit_id`),
  CONSTRAINT `credit_settlements_credit_id_foreign` FOREIGN KEY (`credit_id`) REFERENCES `credits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `credits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `creditable_type` varchar(255) DEFAULT NULL,
  `creditable_id` bigint(20) unsigned DEFAULT NULL,
  `cash_amount` decimal(22,2) NOT NULL,
  `credit_amount` decimal(22,2) NOT NULL,
  `credit_amount_settled` decimal(22,2) NOT NULL,
  `description` longtext DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `last_settled_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `credits_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `credits_created_by_foreign` (`created_by`),
  KEY `credits_updated_by_foreign` (`updated_by`),
  KEY `credits_company_id_index` (`company_id`),
  KEY `credits_warehouse_id_index` (`warehouse_id`),
  KEY `credits_customer_id_index` (`customer_id`),
  KEY `credits_creditable_type_creditable_id_index` (`creditable_type`,`creditable_id`),
  CONSTRAINT `credits_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `credits_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `credits_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `credits_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `credits_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `custom_field_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_field_values` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `custom_field_id` bigint(20) unsigned NOT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `custom_field_valuable_type` varchar(255) NOT NULL,
  `custom_field_valuable_id` bigint(20) unsigned NOT NULL,
  `value` longtext NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `field_valuable` (`custom_field_valuable_type`,`custom_field_valuable_id`),
  KEY `custom_field_values_custom_field_id_index` (`custom_field_id`),
  KEY `custom_field_values_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `custom_field_values_custom_field_id_foreign` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `custom_field_values_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_fields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `placeholder` varchar(255) DEFAULT NULL,
  `options` longtext DEFAULT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  `model_type` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `column_size` varchar(255) DEFAULT 'is-6',
  `icon` varchar(255) DEFAULT 'fas fa-file',
  `is_active` tinyint(1) NOT NULL,
  `is_required` tinyint(1) NOT NULL,
  `is_unique` tinyint(1) NOT NULL,
  `is_visible` tinyint(1) NOT NULL,
  `is_printable` tinyint(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_fields_created_by_foreign` (`created_by`),
  KEY `custom_fields_updated_by_foreign` (`updated_by`),
  KEY `custom_fields_company_id_index` (`company_id`),
  KEY `custom_fields_model_type_index` (`model_type`),
  CONSTRAINT `custom_fields_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `custom_fields_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `custom_fields_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_deposits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_deposits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL,
  `deposited_at` datetime DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_deposits_created_by_foreign` (`created_by`),
  KEY `customer_deposits_updated_by_foreign` (`updated_by`),
  KEY `customer_deposits_approved_by_foreign` (`approved_by`),
  KEY `customer_deposits_company_id_index` (`company_id`),
  KEY `customer_deposits_customer_id_index` (`customer_id`),
  CONSTRAINT `customer_deposits_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `customer_deposits_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_deposits_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `customer_deposits_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_deposits_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `company_name` varchar(255) NOT NULL,
  `tin` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `balance` decimal(22,2) NOT NULL DEFAULT 0.00,
  `credit_amount_limit` decimal(22,2) NOT NULL,
  `business_license_attachment` varchar(255) DEFAULT NULL,
  `business_license_expires_on` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_company_id_tin_unique` (`company_id`,`tin`),
  KEY `customers_company_id_index` (`company_id`),
  KEY `customers_created_by_foreign` (`created_by`),
  KEY `customers_updated_by_foreign` (`updated_by`),
  CONSTRAINT `customers_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `customers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `damage_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `damage_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `damage_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `merchandise_batch_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `damage_details_damage_id_index` (`damage_id`),
  KEY `damage_details_warehouse_id_foreign` (`warehouse_id`),
  KEY `damage_details_product_id_foreign` (`product_id`),
  KEY `damage_details_merchandise_batch_id_foreign` (`merchandise_batch_id`),
  CONSTRAINT `damage_details_damage_id_foreign` FOREIGN KEY (`damage_id`) REFERENCES `damages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `damage_details_merchandise_batch_id_foreign` FOREIGN KEY (`merchandise_batch_id`) REFERENCES `merchandise_batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `damage_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `damage_details_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `damages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `damages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `description` longtext DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `subtracted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `damages_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `damages_company_id_index` (`company_id`),
  KEY `damages_created_by_foreign` (`created_by`),
  KEY `damages_updated_by_foreign` (`updated_by`),
  KEY `damages_approved_by_foreign` (`approved_by`),
  KEY `damages_warehouse_id_foreign` (`warehouse_id`),
  KEY `damages_subtracted_by_foreign` (`subtracted_by`),
  CONSTRAINT `damages_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `damages_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `damages_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `damages_subtracted_by_foreign` FOREIGN KEY (`subtracted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `damages_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `damages_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `debt_settlements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `debt_settlements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `debt_id` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL,
  `method` varchar(255) NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `settled_at` datetime DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `debt_settlements_debt_id_index` (`debt_id`),
  CONSTRAINT `debt_settlements_debt_id_foreign` FOREIGN KEY (`debt_id`) REFERENCES `debts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `debts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `debts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `purchase_id` bigint(20) unsigned DEFAULT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `cash_amount` decimal(22,2) NOT NULL,
  `debt_amount` decimal(22,2) NOT NULL,
  `debt_amount_settled` decimal(22,2) NOT NULL,
  `description` longtext DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `last_settled_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `debts_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `debts_purchase_id_foreign` (`purchase_id`),
  KEY `debts_created_by_foreign` (`created_by`),
  KEY `debts_updated_by_foreign` (`updated_by`),
  KEY `debts_company_id_index` (`company_id`),
  KEY `debts_warehouse_id_index` (`warehouse_id`),
  KEY `debts_supplier_id_index` (`supplier_id`),
  CONSTRAINT `debts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `debts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `debts_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `debts_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `debts_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `debts_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_company_id_name_unique` (`company_id`,`name`),
  KEY `departments_created_by_foreign` (`created_by`),
  KEY `departments_updated_by_foreign` (`updated_by`),
  KEY `departments_company_id_index` (`company_id`),
  CONSTRAINT `departments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `departments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `departments_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `employee_compensation_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_compensation_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `compensation_id` bigint(20) unsigned DEFAULT NULL,
  `change_count` bigint(20) NOT NULL,
  `amount` decimal(22,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_compensation_histories_compensation_id_foreign` (`compensation_id`),
  KEY `employee_compensation_histories_employee_id_index` (`employee_id`),
  CONSTRAINT `employee_compensation_histories_compensation_id_foreign` FOREIGN KEY (`compensation_id`) REFERENCES `compensations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employee_compensation_histories_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `employee_compensations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_compensations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `compensation_id` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_compensations_employee_id_compensation_id_unique` (`employee_id`,`compensation_id`),
  KEY `employee_compensations_compensation_id_index` (`compensation_id`),
  CONSTRAINT `employee_compensations_compensation_id_foreign` FOREIGN KEY (`compensation_id`) REFERENCES `compensations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employee_compensations_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `employee_transfer_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_transfer_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_transfer_id` bigint(20) unsigned DEFAULT NULL,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_transfer_details_employee_id_foreign` (`employee_id`),
  KEY `employee_transfer_details_warehouse_id_foreign` (`warehouse_id`),
  KEY `employee_transfer_details_employee_transfer_id_index` (`employee_transfer_id`),
  CONSTRAINT `employee_transfer_details_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employee_transfer_details_employee_transfer_id_foreign` FOREIGN KEY (`employee_transfer_id`) REFERENCES `employee_transfers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employee_transfer_details_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `employee_transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_transfers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `issued_on` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_transfers_created_by_foreign` (`created_by`),
  KEY `employee_transfers_updated_by_foreign` (`updated_by`),
  KEY `employee_transfers_approved_by_foreign` (`approved_by`),
  KEY `employee_transfers_company_id_index` (`company_id`),
  CONSTRAINT `employee_transfers_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `employee_transfers_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employee_transfers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `employee_transfers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `position` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `gender` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `tin_number` varchar(255) DEFAULT NULL,
  `job_type` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `id_type` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `date_of_hiring` datetime DEFAULT NULL,
  `date_of_birth` datetime DEFAULT NULL,
  `emergency_name` varchar(255) DEFAULT NULL,
  `emergency_phone` varchar(255) DEFAULT NULL,
  `paid_time_off_amount` decimal(22,2) DEFAULT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `does_receive_sales_report_email` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `employees_company_id_index` (`company_id`),
  KEY `employees_user_id_index` (`user_id`),
  KEY `employees_created_by_foreign` (`created_by`),
  KEY `employees_updated_by_foreign` (`updated_by`),
  KEY `employees_department_id_foreign` (`department_id`),
  CONSTRAINT `employees_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employees_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `employees_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `exchange_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exchange_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `exchange_detailable_type` varchar(255) DEFAULT NULL,
  `exchange_detailable_id` bigint(20) unsigned DEFAULT NULL,
  `returned_quantity` decimal(22,2) NOT NULL,
  `exchange_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `merchandise_batch_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `unit_price` decimal(22,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exchangedetail` (`exchange_detailable_type`,`exchange_detailable_id`),
  KEY `exchange_details_merchandise_batch_id_foreign` (`merchandise_batch_id`),
  KEY `exchange_details_exchange_id_index` (`exchange_id`),
  KEY `exchange_details_warehouse_id_index` (`warehouse_id`),
  KEY `exchange_details_product_id_index` (`product_id`),
  CONSTRAINT `exchange_details_exchange_id_foreign` FOREIGN KEY (`exchange_id`) REFERENCES `exchanges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exchange_details_merchandise_batch_id_foreign` FOREIGN KEY (`merchandise_batch_id`) REFERENCES `merchandise_batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `exchange_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exchange_details_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `exchanges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exchanges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `return_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `executed_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `exchangeable_type` varchar(255) DEFAULT NULL,
  `exchangeable_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exchanges_company_id_foreign` (`company_id`),
  KEY `exchanges_warehouse_id_foreign` (`warehouse_id`),
  KEY `exchanges_created_by_foreign` (`created_by`),
  KEY `exchanges_updated_by_foreign` (`updated_by`),
  KEY `exchanges_approved_by_foreign` (`approved_by`),
  KEY `exchanges_executed_by_foreign` (`executed_by`),
  KEY `exchange` (`exchangeable_type`,`exchangeable_id`),
  KEY `exchanges_return_id_index` (`return_id`),
  CONSTRAINT `exchanges_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `exchanges_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exchanges_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `exchanges_executed_by_foreign` FOREIGN KEY (`executed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `exchanges_return_id_foreign` FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `exchanges_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `exchanges_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `expense_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expense_categories_warehouse_id_name_unique` (`warehouse_id`,`name`),
  KEY `expense_categories_created_by_foreign` (`created_by`),
  KEY `expense_categories_updated_by_foreign` (`updated_by`),
  KEY `expense_categories_company_id_index` (`company_id`),
  KEY `expense_categories_warehouse_id_index` (`warehouse_id`),
  CONSTRAINT `expense_categories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `expense_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `expense_categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `expense_categories_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `expense_claim_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_claim_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `expense_claim_id` bigint(20) unsigned DEFAULT NULL,
  `item` varchar(255) NOT NULL,
  `price` decimal(22,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expense_claim_details_expense_claim_id_index` (`expense_claim_id`),
  CONSTRAINT `expense_claim_details_expense_claim_id_foreign` FOREIGN KEY (`expense_claim_id`) REFERENCES `expense_claims` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `expense_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_claims` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `rejected_by` bigint(20) unsigned DEFAULT NULL,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `issued_on` datetime DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expense_claims_warehouse_id_code_unique` (`warehouse_id`,`code`),
  KEY `expense_claims_created_by_foreign` (`created_by`),
  KEY `expense_claims_updated_by_foreign` (`updated_by`),
  KEY `expense_claims_approved_by_foreign` (`approved_by`),
  KEY `expense_claims_rejected_by_foreign` (`rejected_by`),
  KEY `expense_claims_employee_id_foreign` (`employee_id`),
  KEY `expense_claims_company_id_index` (`company_id`),
  KEY `expense_claims_warehouse_id_index` (`warehouse_id`),
  CONSTRAINT `expense_claims_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `expense_claims_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `expense_claims_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `expense_claims_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `expense_claims_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `expense_claims_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `expense_claims_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `expense_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `expense_category_id` bigint(20) unsigned DEFAULT NULL,
  `expense_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `unit_price` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expense_details_expense_category_id_index` (`expense_category_id`),
  KEY `expense_details_expense_id_index` (`expense_id`),
  CONSTRAINT `expense_details_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `expense_details_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `tax_id` bigint(20) unsigned DEFAULT NULL,
  `contact_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `issued_on` datetime DEFAULT NULL,
  `reference_number` bigint(20) DEFAULT NULL,
  `payment_type` varchar(255) NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_reference_number` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expenses_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `expenses_supplier_id_foreign` (`supplier_id`),
  KEY `expenses_created_by_foreign` (`created_by`),
  KEY `expenses_updated_by_foreign` (`updated_by`),
  KEY `expenses_approved_by_foreign` (`approved_by`),
  KEY `expenses_company_id_index` (`company_id`),
  KEY `expenses_warehouse_id_index` (`warehouse_id`),
  KEY `expenses_contact_id_foreign` (`contact_id`),
  KEY `expenses_tax_id_foreign` (`tax_id`),
  CONSTRAINT `expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `expenses_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `expenses_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `expenses_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `expenses_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `expenses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `expenses_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `featurables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `featurables` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feature_id` bigint(20) unsigned DEFAULT NULL,
  `featurable_id` bigint(20) NOT NULL,
  `featurable_type` varchar(255) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `featurables_feature_id_featurable_id_featurable_type_unique` (`feature_id`,`featurable_id`,`featurable_type`),
  CONSTRAINT `featurables_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `features_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gdn_detail_reports`;
/*!50001 DROP VIEW IF EXISTS `gdn_detail_reports`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `gdn_detail_reports` AS SELECT
 1 AS `gdn_id`,
  1 AS `master_id`,
  1 AS `gdn_master_report_id`,
  1 AS `product_category_id`,
  1 AS `product_category_name`,
  1 AS `product_id`,
  1 AS `product_name`,
  1 AS `product_code`,
  1 AS `product_unit_of_measurement`,
  1 AS `warehouse_id`,
  1 AS `warehouse_name`,
  1 AS `quantity`,
  1 AS `unit_price`,
  1 AS `unit_cost`,
  1 AS `discount`,
  1 AS `brand_name`,
  1 AS `line_price_before_tax`,
  1 AS `line_tax` */;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `gdn_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gdn_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gdn_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `merchandise_batch_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `delivered_quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `returned_quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_price` decimal(22,2) DEFAULT NULL,
  `discount` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gdn_details_gdn_id_index` (`gdn_id`),
  KEY `gdn_details_warehouse_id_foreign` (`warehouse_id`),
  KEY `gdn_details_product_id_foreign` (`product_id`),
  KEY `gdn_details_merchandise_batch_id_foreign` (`merchandise_batch_id`),
  CONSTRAINT `gdn_details_gdn_id_foreign` FOREIGN KEY (`gdn_id`) REFERENCES `gdns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `gdn_details_merchandise_batch_id_foreign` FOREIGN KEY (`merchandise_batch_id`) REFERENCES `merchandise_batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `gdn_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `gdn_details_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gdn_master_reports`;
/*!50001 DROP VIEW IF EXISTS `gdn_master_reports`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `gdn_master_reports` AS SELECT
 1 AS `id`,
  1 AS `company_id`,
  1 AS `created_by`,
  1 AS `user_name`,
  1 AS `warehouse_id`,
  1 AS `branch_id`,
  1 AS `branch_name`,
  1 AS `code`,
  1 AS `customer_id`,
  1 AS `customer_name`,
  1 AS `customer_address`,
  1 AS `customer_created_at`,
  1 AS `payment_type`,
  1 AS `bank_name`,
  1 AS `reference_number`,
  1 AS `cash_received_type`,
  1 AS `cash_received`,
  1 AS `discount`,
  1 AS `issued_on`,
  1 AS `subtotal_price`,
  1 AS `total_tax`,
  1 AS `credit_amount`,
  1 AS `credit_amount_settled`,
  1 AS `credit_amount_unsettled`,
  1 AS `last_settled_at` */;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `gdns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gdns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `sale_id` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `contact_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `is_converted_to_sale` tinyint(1) NOT NULL DEFAULT 0,
  `discount` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) NOT NULL,
  `cash_received_type` varchar(255) NOT NULL,
  `cash_received` decimal(22,2) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `subtracted_by` bigint(20) unsigned DEFAULT NULL,
  `added_by` bigint(20) unsigned DEFAULT NULL,
  `cancelled_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gdns_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `gdns_company_id_index` (`company_id`),
  KEY `gdns_sale_id_index` (`sale_id`),
  KEY `gdns_customer_id_foreign` (`customer_id`),
  KEY `gdns_created_by_foreign` (`created_by`),
  KEY `gdns_updated_by_foreign` (`updated_by`),
  KEY `gdns_approved_by_foreign` (`approved_by`),
  KEY `gdns_warehouse_id_foreign` (`warehouse_id`),
  KEY `gdns_subtracted_by_foreign` (`subtracted_by`),
  KEY `gdns_contact_id_foreign` (`contact_id`),
  KEY `gdns_added_by_foreign` (`added_by`),
  KEY `gdns_cancelled_by_foreign` (`cancelled_by`),
  CONSTRAINT `gdns_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `gdns_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `gdns_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `gdns_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `gdns_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `gdns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `gdns_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `gdns_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `gdns_subtracted_by_foreign` FOREIGN KEY (`subtracted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `gdns_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `gdns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `general_tender_checklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `general_tender_checklists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tender_checklist_type_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `item` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `general_tender_checklists_company_id_index` (`company_id`),
  KEY `general_tender_checklists_created_by_foreign` (`created_by`),
  KEY `general_tender_checklists_updated_by_foreign` (`updated_by`),
  KEY `general_tender_checklists_tender_checklist_type_id_foreign` (`tender_checklist_type_id`),
  CONSTRAINT `general_tender_checklists_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `general_tender_checklists_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `general_tender_checklists_tender_checklist_type_id_foreign` FOREIGN KEY (`tender_checklist_type_id`) REFERENCES `tender_checklist_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `general_tender_checklists_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `grn_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grn_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grn_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `unit_cost` decimal(30,10) DEFAULT 0.0000000000,
  `batch_no` varchar(255) DEFAULT NULL,
  `expires_on` date DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grn_details_grn_id_index` (`grn_id`),
  KEY `grn_details_warehouse_id_foreign` (`warehouse_id`),
  KEY `grn_details_product_id_foreign` (`product_id`),
  CONSTRAINT `grn_details_grn_id_foreign` FOREIGN KEY (`grn_id`) REFERENCES `grns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grn_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grn_details_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `grns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `purchase_id` bigint(20) unsigned DEFAULT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `description` longtext DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `added_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grns_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `grns_company_id_index` (`company_id`),
  KEY `grns_purchase_id_index` (`purchase_id`),
  KEY `grns_supplier_id_foreign` (`supplier_id`),
  KEY `grns_created_by_foreign` (`created_by`),
  KEY `grns_updated_by_foreign` (`updated_by`),
  KEY `grns_approved_by_foreign` (`approved_by`),
  KEY `grns_warehouse_id_foreign` (`warehouse_id`),
  KEY `grns_added_by_foreign` (`added_by`),
  CONSTRAINT `grns_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `grns_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `grns_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `grns_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `grns_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `grns_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `grns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `integrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `integrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `integrations_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `inventory_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) NOT NULL,
  `model_detail_type` varchar(255) DEFAULT NULL,
  `model_detail_id` bigint(20) unsigned DEFAULT NULL,
  `is_subtract` tinyint(1) NOT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `issued_on` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_histories_model_detail_is_subtract` (`product_id`,`model_detail_type`,`model_detail_id`,`is_subtract`),
  KEY `inventory_histories_product_id_index` (`product_id`),
  KEY `inventory_histories_warehouse_id_index` (`warehouse_id`),
  KEY `inventory_histories_company_id_index` (`company_id`),
  KEY `inventory_histories_model_detail_type_model_detail_id_index` (`model_detail_type`,`model_detail_id`),
  CONSTRAINT `inventory_histories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_histories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_histories_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `inventory_valuation_balances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_valuation_balances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `unit_cost` decimal(30,10) NOT NULL,
  `operation` varchar(20) NOT NULL DEFAULT 'initial',
  `model_detail_type` varchar(20) DEFAULT NULL,
  `model_detail_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `original_quantity` decimal(22,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_valuation_balances_model_detail_type` (`product_id`,`model_detail_type`,`model_detail_id`,`type`,`operation`) USING HASH,
  KEY `inventory_valuation_balances_company_id_index` (`company_id`),
  KEY `inventory_valuation_balances_product_id_index` (`product_id`),
  KEY `inventory_valuation_balances_model_detail_morph_index` (`model_detail_type`,`model_detail_id`),
  CONSTRAINT `inventory_valuation_balances_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_valuation_balances_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `inventory_valuation_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_valuation_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `unit_cost` decimal(30,10) NOT NULL,
  `operation` varchar(20) NOT NULL DEFAULT 'initial',
  `model_detail_type` varchar(20) DEFAULT NULL,
  `model_detail_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_valuation_histories_model_detail_type` (`product_id`,`model_detail_type`,`model_detail_id`,`type`,`operation`) USING HASH,
  KEY `inventory_valuation_histories_company_id_index` (`company_id`),
  KEY `inventory_valuation_histories_product_id_index` (`product_id`),
  KEY `inventory_valuation_histories_model_detail_morph_index` (`model_detail_type`,`model_detail_id`),
  CONSTRAINT `inventory_valuation_histories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_valuation_histories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_detail_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_detail_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_detail_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_detail_histories_job_detail_id_index` (`job_detail_id`),
  KEY `job_detail_histories_product_id_index` (`product_id`),
  CONSTRAINT `job_detail_histories_job_detail_id_foreign` FOREIGN KEY (`job_detail_id`) REFERENCES `job_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `job_detail_histories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_order_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `bill_of_material_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `wip` decimal(22,2) NOT NULL DEFAULT 0.00,
  `available` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_details_job_order_id_index` (`job_order_id`),
  KEY `job_details_product_id_index` (`product_id`),
  KEY `job_details_bill_of_material_id_index` (`bill_of_material_id`),
  CONSTRAINT `job_details_bill_of_material_id_foreign` FOREIGN KEY (`bill_of_material_id`) REFERENCES `bill_of_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `job_details_job_order_id_foreign` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `job_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_extras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_extras` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_order_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `executed_by` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_extras_executed_by_foreign` (`executed_by`),
  KEY `job_extras_job_order_id_index` (`job_order_id`),
  KEY `job_extras_product_id_index` (`product_id`),
  CONSTRAINT `job_extras_executed_by_foreign` FOREIGN KEY (`executed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `job_extras_job_order_id_foreign` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `job_extras_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `factory_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `is_internal_job` tinyint(1) NOT NULL,
  `description` longtext DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_orders_customer_id_foreign` (`customer_id`),
  KEY `job_orders_factory_id_foreign` (`factory_id`),
  KEY `job_orders_created_by_foreign` (`created_by`),
  KEY `job_orders_updated_by_foreign` (`updated_by`),
  KEY `job_orders_approved_by_foreign` (`approved_by`),
  KEY `job_orders_warehouse_id_index` (`warehouse_id`),
  KEY `job_orders_company_id_index` (`company_id`),
  KEY `job_orders_closed_by_foreign` (`closed_by`),
  CONSTRAINT `job_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `job_orders_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `job_orders_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `job_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `job_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `job_orders_factory_id_foreign` FOREIGN KEY (`factory_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `job_orders_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `job_orders_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `leave_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leave_categories_company_id_name_unique` (`company_id`,`name`),
  KEY `leave_categories_created_by_foreign` (`created_by`),
  KEY `leave_categories_updated_by_foreign` (`updated_by`),
  KEY `leave_categories_company_id_index` (`company_id`),
  CONSTRAINT `leave_categories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `leave_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `leave_categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leaves` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `cancelled_by` bigint(20) unsigned DEFAULT NULL,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `leave_category_id` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `starting_period` datetime DEFAULT NULL,
  `ending_period` datetime DEFAULT NULL,
  `is_paid_time_off` tinyint(1) NOT NULL,
  `time_off_amount` decimal(22,2) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leaves_created_by_foreign` (`created_by`),
  KEY `leaves_updated_by_foreign` (`updated_by`),
  KEY `leaves_approved_by_foreign` (`approved_by`),
  KEY `leaves_cancelled_by_foreign` (`cancelled_by`),
  KEY `leaves_employee_id_foreign` (`employee_id`),
  KEY `leaves_leave_category_id_index` (`leave_category_id`),
  KEY `leaves_company_id_index` (`company_id`),
  KEY `leaves_warehouse_id_index` (`warehouse_id`),
  CONSTRAINT `leaves_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `leaves_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `leaves_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `leaves_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `leaves_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `leaves_leave_category_id_foreign` FOREIGN KEY (`leave_category_id`) REFERENCES `leave_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `leaves_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `leaves_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `limitables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `limitables` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `limit_id` bigint(20) unsigned DEFAULT NULL,
  `limitable_id` bigint(20) NOT NULL,
  `limitable_type` varchar(255) NOT NULL,
  `amount` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `limitables_limit_id_limitable_id_limitable_type_unique` (`limit_id`,`limitable_id`,`limitable_type`),
  CONSTRAINT `limitables_limit_id_foreign` FOREIGN KEY (`limit_id`) REFERENCES `limits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `limits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `limits_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `merchandise_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `merchandise_batches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `merchandise_id` bigint(20) unsigned DEFAULT NULL,
  `batch_no` varchar(255) DEFAULT NULL,
  `expires_on` date DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `received_quantity` decimal(22,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `merchandise_batches_merchandise_id_index` (`merchandise_id`),
  KEY `merchandise_batches_batch_no_index` (`batch_no`),
  CONSTRAINT `merchandise_batches_merchandise_id_foreign` FOREIGN KEY (`merchandise_id`) REFERENCES `merchandises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `merchandises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `merchandises` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `available` decimal(22,2) NOT NULL DEFAULT 0.00,
  `reserved` decimal(22,2) NOT NULL DEFAULT 0.00,
  `wip` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `merchandises_product_id_warehouse_id_unique` (`product_id`,`warehouse_id`),
  KEY `merchandises_product_id_index` (`product_id`),
  KEY `merchandises_company_id_index` (`company_id`),
  KEY `merchandises_warehouse_id_index` (`warehouse_id`),
  CONSTRAINT `merchandises_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `merchandises_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `merchandises_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pad_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pad_fields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pad_id` bigint(20) unsigned DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `is_master_field` tinyint(1) NOT NULL,
  `is_required` tinyint(1) NOT NULL,
  `is_visible` tinyint(1) NOT NULL,
  `is_printable` tinyint(1) NOT NULL,
  `is_readonly` tinyint(1) NOT NULL DEFAULT 0,
  `tag` varchar(255) NOT NULL,
  `tag_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pad_fields_pad_id_foreign` (`pad_id`),
  CONSTRAINT `pad_fields_pad_id_foreign` FOREIGN KEY (`pad_id`) REFERENCES `pads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pad_permission_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pad_permission_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pad_permission_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pad_permission_user_pad_permission_id_user_id_unique` (`pad_permission_id`,`user_id`),
  KEY `pad_permission_user_user_id_foreign` (`user_id`),
  CONSTRAINT `pad_permission_user_pad_permission_id_foreign` FOREIGN KEY (`pad_permission_id`) REFERENCES `pad_permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pad_permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pad_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pad_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pad_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pad_permissions_pad_id_name_unique` (`pad_id`,`name`),
  CONSTRAINT `pad_permissions_pad_id_foreign` FOREIGN KEY (`pad_id`) REFERENCES `pads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pad_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pad_relations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pad_field_id` bigint(20) unsigned DEFAULT NULL,
  `relationship_type` varchar(255) NOT NULL,
  `model_name` varchar(255) NOT NULL,
  `representative_column` varchar(255) NOT NULL,
  `component_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pad_relations_pad_field_id_foreign` (`pad_field_id`),
  CONSTRAINT `pad_relations_pad_field_id_foreign` FOREIGN KEY (`pad_field_id`) REFERENCES `pad_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pad_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pad_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pad_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `text_color` varchar(255) NOT NULL,
  `bg_color` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_editable` tinyint(1) NOT NULL,
  `is_deletable` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pad_statuses_pad_id_foreign` (`pad_id`),
  CONSTRAINT `pad_statuses_pad_id_foreign` FOREIGN KEY (`pad_id`) REFERENCES `pads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `abbreviation` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `inventory_operation_type` varchar(255) NOT NULL,
  `is_approvable` tinyint(1) NOT NULL,
  `is_printable` tinyint(1) NOT NULL,
  `has_prices` tinyint(1) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `module` varchar(255) NOT NULL,
  `convert_to` varchar(255) DEFAULT NULL,
  `convert_from` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `print_orientation` varchar(255) NOT NULL DEFAULT 'portrait',
  `print_paper_size` varchar(255) NOT NULL DEFAULT 'A4',
  PRIMARY KEY (`id`),
  KEY `pads_company_id_foreign` (`company_id`),
  KEY `pads_created_by_foreign` (`created_by`),
  KEY `pads_updated_by_foreign` (`updated_by`),
  CONSTRAINT `pads_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pads_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `pads_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payroll_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payroll_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payroll_id` bigint(20) unsigned DEFAULT NULL,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `compensation_id` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payroll_details_payroll_id_foreign` (`payroll_id`),
  KEY `payroll_details_employee_id_foreign` (`employee_id`),
  KEY `payroll_details_compensation_id_foreign` (`compensation_id`),
  CONSTRAINT `payroll_details_compensation_id_foreign` FOREIGN KEY (`compensation_id`) REFERENCES `compensations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payroll_details_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payroll_details_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payrolls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payrolls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payrolls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `paid_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `working_days` decimal(8,2) DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `starting_period` date DEFAULT NULL,
  `ending_period` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payrolls_company_id_starting_period_ending_period_unique` (`company_id`,`starting_period`,`ending_period`),
  KEY `payrolls_created_by_foreign` (`created_by`),
  KEY `payrolls_updated_by_foreign` (`updated_by`),
  KEY `payrolls_approved_by_foreign` (`approved_by`),
  KEY `payrolls_paid_by_foreign` (`paid_by`),
  KEY `payrolls_company_id_index` (`company_id`),
  CONSTRAINT `payrolls_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payrolls_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payrolls_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payrolls_paid_by_foreign` FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payrolls_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `price_increment_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `price_increment_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `price_increment_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_increment_details_product_id_foreign` (`product_id`),
  KEY `price_increment_details_price_increment_id_index` (`price_increment_id`),
  CONSTRAINT `price_increment_details_price_increment_id_foreign` FOREIGN KEY (`price_increment_id`) REFERENCES `price_increments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `price_increment_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `price_increments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `price_increments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `target_product` varchar(255) NOT NULL,
  `price_type` varchar(255) NOT NULL,
  `price_increment` decimal(22,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_increments_created_by_foreign` (`created_by`),
  KEY `price_increments_updated_by_foreign` (`updated_by`),
  KEY `price_increments_approved_by_foreign` (`approved_by`),
  KEY `price_increments_company_id_index` (`company_id`),
  CONSTRAINT `price_increments_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `price_increments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `price_increments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `price_increments_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `company_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `fixed_price` decimal(22,2) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prices_created_by_foreign` (`created_by`),
  KEY `prices_updated_by_foreign` (`updated_by`),
  KEY `prices_company_id_index` (`company_id`),
  KEY `prices_product_id_foreign` (`product_id`),
  CONSTRAINT `prices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `prices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prices_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_bundles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_bundles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `component_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_bundles_product_id_index` (`product_id`),
  KEY `product_bundles_component_id_index` (`component_id`),
  CONSTRAINT `product_bundles_component_id_foreign` FOREIGN KEY (`component_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_bundles_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_categories_company_id_index` (`company_id`),
  KEY `product_categories_created_by_foreign` (`created_by`),
  KEY `product_categories_updated_by_foreign` (`updated_by`),
  CONSTRAINT `product_categories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `product_categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_reorders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_reorders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_reorders_product_id_warehouse_id_unique` (`product_id`,`warehouse_id`),
  KEY `product_reorders_company_id_foreign` (`company_id`),
  KEY `product_reorders_created_by_foreign` (`created_by`),
  KEY `product_reorders_updated_by_foreign` (`updated_by`),
  KEY `product_reorders_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `product_reorders_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_reorders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `product_reorders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_reorders_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `product_reorders_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_category_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `brand_id` bigint(20) unsigned DEFAULT NULL,
  `tax_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `unit_of_measurement` varchar(255) NOT NULL,
  `min_on_hand` decimal(22,2) NOT NULL,
  `is_batchable` tinyint(1) NOT NULL DEFAULT 0,
  `batch_priority` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_product_single` tinyint(1) NOT NULL DEFAULT 1,
  `is_active_for_sale` tinyint(1) NOT NULL DEFAULT 1,
  `is_active_for_purchase` tinyint(1) NOT NULL DEFAULT 1,
  `is_active_for_job` tinyint(1) NOT NULL DEFAULT 1,
  `inventory_valuation_method` varchar(255) NOT NULL DEFAULT 'average',
  `lifo_unit_cost` decimal(30,10) NOT NULL DEFAULT 0.0000000000,
  `fifo_unit_cost` decimal(30,10) NOT NULL DEFAULT 0.0000000000,
  `average_unit_cost` decimal(30,10) NOT NULL DEFAULT 0.0000000000,
  `profit_margin_type` varchar(255) NOT NULL DEFAULT 'percent',
  `profit_margin_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_product_category_id_index` (`product_category_id`),
  KEY `products_company_id_index` (`company_id`),
  KEY `products_supplier_id_index` (`supplier_id`),
  KEY `products_created_by_foreign` (`created_by`),
  KEY `products_updated_by_foreign` (`updated_by`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_tax_id_foreign` (`tax_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `products_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `products_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `products_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `products_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `proforma_invoice_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proforma_invoice_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proforma_invoice_id` bigint(20) unsigned DEFAULT NULL,
  `custom_product` varchar(255) DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `merchandise_batch_id` bigint(20) unsigned DEFAULT NULL,
  `specification` longtext DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `unit_price` decimal(22,2) NOT NULL,
  `discount` decimal(22,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proforma_invoice_details_proforma_invoice_id_index` (`proforma_invoice_id`),
  KEY `proforma_invoice_details_product_id_foreign` (`product_id`),
  KEY `proforma_invoice_details_merchandise_batch_id_foreign` (`merchandise_batch_id`),
  CONSTRAINT `proforma_invoice_details_merchandise_batch_id_foreign` FOREIGN KEY (`merchandise_batch_id`) REFERENCES `merchandise_batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `proforma_invoice_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `proforma_invoice_details_proforma_invoice_id_foreign` FOREIGN KEY (`proforma_invoice_id`) REFERENCES `proforma_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `proforma_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proforma_invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `converted_by` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `contact_id` bigint(20) unsigned DEFAULT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `proforma_invoiceable_type` varchar(255) DEFAULT NULL,
  `proforma_invoiceable_id` bigint(20) unsigned DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `discount` varchar(255) DEFAULT NULL,
  `is_pending` tinyint(1) NOT NULL,
  `terms` longtext DEFAULT NULL,
  `expires_on` datetime DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proforma_invoices_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `proforma_invoices_company_id_index` (`company_id`),
  KEY `proforma_invoices_customer_id_index` (`customer_id`),
  KEY `proforma_invoices_created_by_foreign` (`created_by`),
  KEY `proforma_invoices_updated_by_foreign` (`updated_by`),
  KEY `proforma_invoices_converted_by_foreign` (`converted_by`),
  KEY `proforma_invoices_warehouse_id_foreign` (`warehouse_id`),
  KEY `proforma_invoices_contact_id_foreign` (`contact_id`),
  KEY `pi` (`proforma_invoiceable_type`,`proforma_invoiceable_id`),
  CONSTRAINT `proforma_invoices_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `proforma_invoices_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `proforma_invoices_converted_by_foreign` FOREIGN KEY (`converted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `proforma_invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `proforma_invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `proforma_invoices_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `proforma_invoices_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `purchase_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `unit_price` decimal(30,10) NOT NULL,
  `amount` decimal(22,2) DEFAULT NULL,
  `duty_rate` decimal(22,2) DEFAULT NULL,
  `excise_tax` decimal(22,2) DEFAULT NULL,
  `vat_rate` decimal(22,2) DEFAULT NULL,
  `surtax` decimal(22,2) DEFAULT NULL,
  `withholding_tax` decimal(22,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `batch_no` varchar(255) DEFAULT NULL,
  `expires_on` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_details_purchase_id_index` (`purchase_id`),
  KEY `purchase_details_product_id_index` (`product_id`),
  KEY `purchase_details_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `purchase_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_details_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_details_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchases` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `tax_id` bigint(20) unsigned DEFAULT NULL,
  `contact_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `purchased_by` bigint(20) unsigned DEFAULT NULL,
  `rejected_by` bigint(20) unsigned DEFAULT NULL,
  `cancelled_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL,
  `payment_type` varchar(255) NOT NULL,
  `cash_paid_type` varchar(255) NOT NULL,
  `cash_paid` decimal(22,2) NOT NULL,
  `due_date` datetime DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `exchange_rate` decimal(30,10) DEFAULT NULL,
  `freight_cost` decimal(22,2) DEFAULT NULL,
  `freight_insurance_cost` decimal(22,2) DEFAULT NULL,
  `freight_unit` varchar(255) DEFAULT NULL,
  `other_costs_before_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `other_costs_after_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `purchased_on` datetime DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchases_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `purchases_company_id_index` (`company_id`),
  KEY `purchases_supplier_id_index` (`supplier_id`),
  KEY `purchases_created_by_foreign` (`created_by`),
  KEY `purchases_updated_by_foreign` (`updated_by`),
  KEY `purchases_warehouse_id_foreign` (`warehouse_id`),
  KEY `purchases_approved_by_foreign` (`approved_by`),
  KEY `purchases_purchased_by_foreign` (`purchased_by`),
  KEY `purchases_contact_id_foreign` (`contact_id`),
  KEY `purchases_rejected_by_foreign` (`rejected_by`),
  KEY `purchases_cancelled_by_foreign` (`cancelled_by`),
  KEY `purchases_tax_id_foreign` (`tax_id`),
  CONSTRAINT `purchases_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchases_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchases_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchases_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchases_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchases_purchased_by_foreign` FOREIGN KEY (`purchased_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchases_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchases_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchases_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchases_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `push_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `push_subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subscribable_type` varchar(255) NOT NULL,
  `subscribable_id` bigint(20) unsigned NOT NULL,
  `endpoint` varchar(500) NOT NULL,
  `public_key` varchar(255) DEFAULT NULL,
  `auth_token` varchar(255) DEFAULT NULL,
  `content_encoding` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `push_subscriptions_endpoint_unique` (`endpoint`),
  KEY `push_subscriptions_subscribable_type_subscribable_id_index` (`subscribable_type`,`subscribable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reservation_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservation_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `merchandise_batch_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `unit_price` decimal(22,2) DEFAULT NULL,
  `discount` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservation_details_reservation_id_index` (`reservation_id`),
  KEY `reservation_details_warehouse_id_foreign` (`warehouse_id`),
  KEY `reservation_details_product_id_foreign` (`product_id`),
  KEY `reservation_details_merchandise_batch_id_foreign` (`merchandise_batch_id`),
  CONSTRAINT `reservation_details_merchandise_batch_id_foreign` FOREIGN KEY (`merchandise_batch_id`) REFERENCES `merchandise_batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservation_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservation_details_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservation_details_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `contact_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `reserved_by` bigint(20) unsigned DEFAULT NULL,
  `cancelled_by` bigint(20) unsigned DEFAULT NULL,
  `converted_by` bigint(20) unsigned DEFAULT NULL,
  `reservable_type` varchar(255) DEFAULT NULL,
  `reservable_id` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `has_withholding` tinyint(1) DEFAULT NULL,
  `discount` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) NOT NULL,
  `cash_received_type` varchar(255) NOT NULL,
  `cash_received` decimal(22,2) NOT NULL,
  `description` longtext DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `expires_on` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reservations_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `reservations_reservable_type_reservable_id_index` (`reservable_type`,`reservable_id`),
  KEY `reservations_company_id_index` (`company_id`),
  KEY `reservations_customer_id_foreign` (`customer_id`),
  KEY `reservations_created_by_foreign` (`created_by`),
  KEY `reservations_updated_by_foreign` (`updated_by`),
  KEY `reservations_approved_by_foreign` (`approved_by`),
  KEY `reservations_reserved_by_foreign` (`reserved_by`),
  KEY `reservations_cancelled_by_foreign` (`cancelled_by`),
  KEY `reservations_converted_by_foreign` (`converted_by`),
  KEY `reservations_warehouse_id_foreign` (`warehouse_id`),
  KEY `reservations_contact_id_foreign` (`contact_id`),
  CONSTRAINT `reservations_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservations_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_converted_by_foreign` FOREIGN KEY (`converted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_reserved_by_foreign` FOREIGN KEY (`reserved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reservations_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `return_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `return_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `return_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `merchandise_batch_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `unit_price` decimal(22,2) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `return_details_return_id_index` (`return_id`),
  KEY `return_details_warehouse_id_foreign` (`warehouse_id`),
  KEY `return_details_product_id_foreign` (`product_id`),
  KEY `return_details_merchandise_batch_id_foreign` (`merchandise_batch_id`),
  CONSTRAINT `return_details_merchandise_batch_id_foreign` FOREIGN KEY (`merchandise_batch_id`) REFERENCES `merchandise_batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `return_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `return_details_return_id_foreign` FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `return_details_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `returns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `gdn_id` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `added_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `description` longtext DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `returns_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `returns_company_id_index` (`company_id`),
  KEY `returns_customer_id_index` (`customer_id`),
  KEY `returns_created_by_foreign` (`created_by`),
  KEY `returns_updated_by_foreign` (`updated_by`),
  KEY `returns_approved_by_foreign` (`approved_by`),
  KEY `returns_returned_by_foreign` (`added_by`),
  KEY `returns_warehouse_id_foreign` (`warehouse_id`),
  KEY `returns_gdn_id_foreign` (`gdn_id`),
  CONSTRAINT `returns_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `returns_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `returns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `returns_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `returns_gdn_id_foreign` FOREIGN KEY (`gdn_id`) REFERENCES `gdns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `returns_returned_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `returns_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `returns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sale_detail_reports`;
/*!50001 DROP VIEW IF EXISTS `sale_detail_reports`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `sale_detail_reports` AS SELECT
 1 AS `sale_id`,
  1 AS `master_id`,
  1 AS `sale_master_report_id`,
  1 AS `product_category_id`,
  1 AS `product_category_name`,
  1 AS `product_id`,
  1 AS `product_name`,
  1 AS `product_code`,
  1 AS `product_unit_of_measurement`,
  1 AS `quantity`,
  1 AS `unit_price`,
  1 AS `unit_cost`,
  1 AS `brand_name`,
  1 AS `line_price_before_tax`,
  1 AS `line_tax` */;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `sale_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `merchandise_batch_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `delivered_quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_price` decimal(22,2) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_details_sale_id_index` (`sale_id`),
  KEY `sale_details_product_id_index` (`product_id`),
  KEY `sale_details_merchandise_batch_id_foreign` (`merchandise_batch_id`),
  KEY `sale_details_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `sale_details_merchandise_batch_id_foreign` FOREIGN KEY (`merchandise_batch_id`) REFERENCES `merchandise_batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sale_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sale_details_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sale_details_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sale_master_reports`;
/*!50001 DROP VIEW IF EXISTS `sale_master_reports`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `sale_master_reports` AS SELECT
 1 AS `id`,
  1 AS `company_id`,
  1 AS `created_by`,
  1 AS `user_name`,
  1 AS `warehouse_id`,
  1 AS `branch_id`,
  1 AS `branch_name`,
  1 AS `code`,
  1 AS `customer_id`,
  1 AS `customer_name`,
  1 AS `customer_address`,
  1 AS `customer_created_at`,
  1 AS `fs_number`,
  1 AS `payment_type`,
  1 AS `bank_name`,
  1 AS `reference_number`,
  1 AS `cash_received_type`,
  1 AS `cash_received`,
  1 AS `issued_on`,
  1 AS `subtotal_price`,
  1 AS `total_tax`,
  1 AS `credit_amount`,
  1 AS `credit_amount_settled`,
  1 AS `credit_amount_unsettled`,
  1 AS `last_settled_at` */;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `contact_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `subtracted_by` bigint(20) unsigned DEFAULT NULL,
  `added_by` bigint(20) unsigned DEFAULT NULL,
  `cancelled_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `fs_number` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) NOT NULL,
  `cash_received_type` varchar(255) NOT NULL,
  `cash_received` decimal(22,2) NOT NULL,
  `issued_on` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `has_withholding` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  UNIQUE KEY `sales_company_id_warehouse_id_fs_number_unique` (`company_id`,`warehouse_id`,`fs_number`),
  KEY `sales_company_id_index` (`company_id`),
  KEY `sales_customer_id_index` (`customer_id`),
  KEY `sales_created_by_foreign` (`created_by`),
  KEY `sales_updated_by_foreign` (`updated_by`),
  KEY `sales_warehouse_id_foreign` (`warehouse_id`),
  KEY `sales_approved_by_foreign` (`approved_by`),
  KEY `sales_cancelled_by_foreign` (`cancelled_by`),
  KEY `sales_contact_id_foreign` (`contact_id`),
  KEY `sales_subtracted_by_foreign` (`subtracted_by`),
  KEY `sales_added_by_foreign` (`added_by`),
  CONSTRAINT `sales_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sales_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sales_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sales_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sales_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sales_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sales_subtracted_by_foreign` FOREIGN KEY (`subtracted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sales_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sales_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `siv_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `siv_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `siv_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `merchandise_batch_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `siv_details_siv_id_index` (`siv_id`),
  KEY `siv_details_warehouse_id_foreign` (`warehouse_id`),
  KEY `siv_details_product_id_foreign` (`product_id`),
  KEY `siv_details_merchandise_batch_id_foreign` (`merchandise_batch_id`),
  CONSTRAINT `siv_details_merchandise_batch_id_foreign` FOREIGN KEY (`merchandise_batch_id`) REFERENCES `merchandise_batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `siv_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `siv_details_siv_id_foreign` FOREIGN KEY (`siv_id`) REFERENCES `sivs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `siv_details_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sivs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sivs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `subtracted_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `sivable_type` varchar(255) DEFAULT NULL,
  `sivable_id` bigint(20) unsigned DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `issued_to` varchar(255) DEFAULT NULL,
  `received_by` varchar(255) DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `delivered_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sivs_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `sivs_company_id_index` (`company_id`),
  KEY `sivs_created_by_foreign` (`created_by`),
  KEY `sivs_updated_by_foreign` (`updated_by`),
  KEY `sivs_approved_by_foreign` (`approved_by`),
  KEY `sivs_warehouse_id_foreign` (`warehouse_id`),
  KEY `sivs_subtracted_by_foreign` (`subtracted_by`),
  KEY `siv` (`sivable_type`,`sivable_id`),
  CONSTRAINT `sivs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sivs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sivs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sivs_subtracted_by_foreign` FOREIGN KEY (`subtracted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sivs_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sivs_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `plan_id` bigint(20) unsigned DEFAULT NULL,
  `starts_on` date DEFAULT NULL,
  `months` int(11) NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_company_id_foreign` (`company_id`),
  KEY `subscriptions_plan_id_foreign` (`plan_id`),
  CONSTRAINT `subscriptions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `company_name` varchar(255) NOT NULL,
  `tin` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `debt_amount_limit` decimal(22,2) NOT NULL,
  `business_license_attachment` varchar(255) DEFAULT NULL,
  `business_license_expires_on` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_company_id_tin_unique` (`company_id`,`tin`),
  KEY `suppliers_company_id_index` (`company_id`),
  KEY `suppliers_created_by_foreign` (`created_by`),
  KEY `suppliers_updated_by_foreign` (`updated_by`),
  CONSTRAINT `suppliers_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `suppliers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `suppliers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `taxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taxes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `amount` decimal(22,2) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `taxes_company_id_index` (`company_id`),
  CONSTRAINT `taxes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tender_checklist_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tender_checklist_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `is_sensitive` tinyint(1) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tender_checklist_types_name_company_id_unique` (`name`,`company_id`),
  KEY `tender_checklist_types_company_id_index` (`company_id`),
  KEY `tender_checklist_types_created_by_foreign` (`created_by`),
  KEY `tender_checklist_types_updated_by_foreign` (`updated_by`),
  CONSTRAINT `tender_checklist_types_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tender_checklist_types_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tender_checklist_types_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tender_checklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tender_checklists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tender_id` bigint(20) unsigned DEFAULT NULL,
  `general_tender_checklist_id` bigint(20) unsigned DEFAULT NULL,
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `comment` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tender_checklists_tender_id_index` (`tender_id`),
  KEY `tender_checklists_general_tender_checklist_id_foreign` (`general_tender_checklist_id`),
  KEY `tender_checklists_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `tender_checklists_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tender_checklists_general_tender_checklist_id_foreign` FOREIGN KEY (`general_tender_checklist_id`) REFERENCES `general_tender_checklists` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tender_checklists_tender_id_foreign` FOREIGN KEY (`tender_id`) REFERENCES `tenders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tender_lot_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tender_lot_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tender_lot_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tender_lot_details_product_id_index` (`product_id`),
  KEY `tender_lot_details_tender_lot_id_foreign` (`tender_lot_id`),
  CONSTRAINT `tender_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tender_lot_details_tender_lot_id_foreign` FOREIGN KEY (`tender_lot_id`) REFERENCES `tender_lots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tender_lots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tender_lots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tender_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tender_lots_tender_id_foreign` (`tender_id`),
  CONSTRAINT `tender_lots_tender_id_foreign` FOREIGN KEY (`tender_id`) REFERENCES `tenders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tender_opportunities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tender_opportunities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `tender_status_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `published_on` datetime DEFAULT NULL,
  `body` longtext NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `price` decimal(22,2) DEFAULT NULL,
  `comments` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tender_opportunities_customer_id_foreign` (`customer_id`),
  KEY `tender_opportunities_tender_status_id_foreign` (`tender_status_id`),
  KEY `tender_opportunities_created_by_foreign` (`created_by`),
  KEY `tender_opportunities_updated_by_foreign` (`updated_by`),
  KEY `tender_opportunities_company_id_index` (`company_id`),
  KEY `tender_opportunities_warehouse_id_index` (`warehouse_id`),
  CONSTRAINT `tender_opportunities_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tender_opportunities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tender_opportunities_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tender_opportunities_tender_status_id_foreign` FOREIGN KEY (`tender_status_id`) REFERENCES `tender_statuses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tender_opportunities_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tender_opportunities_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tender_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tender_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tender_statuses_company_id_index` (`company_id`),
  KEY `tender_statuses_created_by_foreign` (`created_by`),
  KEY `tender_statuses_updated_by_foreign` (`updated_by`),
  CONSTRAINT `tender_statuses_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tender_statuses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tender_statuses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tenders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tenders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `bid_bond_amount` varchar(22) DEFAULT NULL,
  `bid_bond_validity` bigint(20) DEFAULT NULL,
  `bid_bond_type` varchar(255) DEFAULT NULL,
  `price` longtext DEFAULT NULL,
  `payment_term` longtext DEFAULT NULL,
  `participants` bigint(20) DEFAULT NULL,
  `published_on` datetime DEFAULT NULL,
  `closing_date` datetime DEFAULT NULL,
  `opening_date` datetime DEFAULT NULL,
  `financial_reading` longtext DEFAULT NULL,
  `technical_reading` longtext DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `clarify_on` datetime DEFAULT NULL,
  `visit_on` datetime DEFAULT NULL,
  `premeet_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenders_company_id_index` (`company_id`),
  KEY `tenders_customer_id_index` (`customer_id`),
  KEY `tenders_created_by_foreign` (`created_by`),
  KEY `tenders_updated_by_foreign` (`updated_by`),
  KEY `tenders_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `tenders_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tenders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tenders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tenders_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tenders_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `transaction_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_fields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) unsigned DEFAULT NULL,
  `pad_field_id` bigint(20) unsigned DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `line` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_fields_transaction_id_pad_field_id_key_value_unique` (`transaction_id`,`pad_field_id`,`key`,`value`) USING HASH,
  KEY `transaction_fields_pad_field_id_foreign` (`pad_field_id`),
  KEY `transaction_fields_transaction_id_index` (`transaction_id`),
  CONSTRAINT `transaction_fields_pad_field_id_foreign` FOREIGN KEY (`pad_field_id`) REFERENCES `pad_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transaction_fields_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pad_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_warehouse_id_pad_id_code_unique` (`warehouse_id`,`pad_id`,`code`),
  KEY `transactions_pad_id_foreign` (`pad_id`),
  KEY `transactions_created_by_foreign` (`created_by`),
  KEY `transactions_updated_by_foreign` (`updated_by`),
  KEY `transactions_company_id_index` (`company_id`),
  CONSTRAINT `transactions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `transactions_pad_id_foreign` FOREIGN KEY (`pad_id`) REFERENCES `pads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transactions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `transactions_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `transfer_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transfer_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transfer_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `merchandise_batch_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transfer_details_transfer_id_index` (`transfer_id`),
  KEY `transfer_details_product_id_foreign` (`product_id`),
  KEY `transfer_details_merchandise_batch_id_foreign` (`merchandise_batch_id`),
  CONSTRAINT `transfer_details_merchandise_batch_id_foreign` FOREIGN KEY (`merchandise_batch_id`) REFERENCES `merchandise_batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `transfer_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transfer_details_transfer_id_foreign` FOREIGN KEY (`transfer_id`) REFERENCES `transfers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transfers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `subtracted_by` bigint(20) unsigned DEFAULT NULL,
  `added_by` bigint(20) unsigned DEFAULT NULL,
  `transferred_from` bigint(20) unsigned DEFAULT NULL,
  `transferred_to` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `description` longtext DEFAULT NULL,
  `issued_on` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transfers_company_id_warehouse_id_code_unique` (`company_id`,`warehouse_id`,`code`),
  KEY `transfers_company_id_index` (`company_id`),
  KEY `transfers_created_by_foreign` (`created_by`),
  KEY `transfers_updated_by_foreign` (`updated_by`),
  KEY `transfers_approved_by_foreign` (`approved_by`),
  KEY `transfers_warehouse_id_foreign` (`warehouse_id`),
  KEY `transfers_subtracted_by_foreign` (`subtracted_by`),
  KEY `transfers_added_by_foreign` (`added_by`),
  KEY `transfers_transferred_from_foreign` (`transferred_from`),
  KEY `transfers_transferred_to_foreign` (`transferred_to`),
  CONSTRAINT `transfers_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `transfers_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `transfers_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transfers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `transfers_subtracted_by_foreign` FOREIGN KEY (`subtracted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `transfers_transferred_from_foreign` FOREIGN KEY (`transferred_from`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transfers_transferred_to_foreign` FOREIGN KEY (`transferred_to`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transfers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `transfers_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_warehouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_warehouse` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_warehouse_user_id_warehouse_id_type_unique` (`user_id`,`warehouse_id`,`type`),
  KEY `user_warehouse_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `user_warehouse_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_warehouse_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `last_online_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `users_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warehouses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_sales_store` tinyint(1) NOT NULL DEFAULT 1,
  `can_be_sold_from` tinyint(1) NOT NULL DEFAULT 1,
  `pos_provider` varchar(255) DEFAULT NULL,
  `host_address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warehouses_company_id_index` (`company_id`),
  KEY `warehouses_created_by_foreign` (`created_by`),
  KEY `warehouses_updated_by_foreign` (`updated_by`),
  CONSTRAINT `warehouses_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `warehouses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `warehouses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `warnings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warnings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `code` bigint(20) NOT NULL,
  `type` varchar(255) NOT NULL,
  `issued_on` datetime DEFAULT NULL,
  `letter` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warnings_created_by_foreign` (`created_by`),
  KEY `warnings_updated_by_foreign` (`updated_by`),
  KEY `warnings_approved_by_foreign` (`approved_by`),
  KEY `warnings_employee_id_foreign` (`employee_id`),
  KEY `warnings_company_id_index` (`company_id`),
  KEY `warnings_warehouse_id_index` (`warehouse_id`),
  CONSTRAINT `warnings_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `warnings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `warnings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `warnings_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `warnings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `warnings_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50001 DROP VIEW IF EXISTS `gdn_detail_reports`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `gdn_detail_reports` AS select `gdn_details`.`gdn_id` AS `gdn_id`,`gdn_details`.`gdn_id` AS `master_id`,`gdn_details`.`gdn_id` AS `gdn_master_report_id`,`product_categories`.`id` AS `product_category_id`,`product_categories`.`name` AS `product_category_name`,`gdn_details`.`product_id` AS `product_id`,`products`.`name` AS `product_name`,`products`.`code` AS `product_code`,`products`.`unit_of_measurement` AS `product_unit_of_measurement`,`gdn_details`.`warehouse_id` AS `warehouse_id`,`warehouses`.`name` AS `warehouse_name`,`gdn_details`.`quantity` AS `quantity`,`gdn_details`.`unit_price` AS `unit_price`,if(`products`.`is_product_single` = 1,(select `inventory_valuation_histories`.`unit_cost` from `inventory_valuation_histories` where `inventory_valuation_histories`.`product_id` = `gdn_details`.`product_id` and `inventory_valuation_histories`.`type` = `products`.`inventory_valuation_method` and `inventory_valuation_histories`.`created_at` <= `gdns`.`issued_on` and `inventory_valuation_histories`.`deleted_at` is null order by `inventory_valuation_histories`.`id` desc limit 1),(select sum(`inventory_valuation_histories`.`unit_cost` * `product_bundles`.`quantity`) from (`product_bundles` join `inventory_valuation_histories` on(`product_bundles`.`component_id` = `inventory_valuation_histories`.`product_id`)) where `product_bundles`.`product_id` = `gdn_details`.`product_id` and `inventory_valuation_histories`.`type` = `products`.`inventory_valuation_method` and `inventory_valuation_histories`.`created_at` <= `gdns`.`issued_on` and `inventory_valuation_histories`.`deleted_at` is null group by `product_bundles`.`product_id` order by `inventory_valuation_histories`.`id` desc)) AS `unit_cost`,`gdn_details`.`discount` AS `discount`,`brands`.`name` AS `brand_name`,if(`gdn_details`.`discount` is null,if(`companies`.`is_price_before_vat` = 1,`gdn_details`.`unit_price`,`gdn_details`.`unit_price` / (1 + `taxes`.`amount`)) * `gdn_details`.`quantity`,if(`companies`.`is_price_before_vat` = 1,`gdn_details`.`unit_price`,`gdn_details`.`unit_price` / (1 + `taxes`.`amount`)) * `gdn_details`.`quantity` - if(`companies`.`is_price_before_vat` = 1,`gdn_details`.`unit_price`,`gdn_details`.`unit_price` / (1 + `taxes`.`amount`)) * `gdn_details`.`quantity` * (`gdn_details`.`discount` / 100)) AS `line_price_before_tax`,if(`gdn_details`.`discount` is null,if(`companies`.`is_price_before_vat` = 1,`gdn_details`.`unit_price`,`gdn_details`.`unit_price` / (1 + `taxes`.`amount`)) * `gdn_details`.`quantity` * `taxes`.`amount`,if(`companies`.`is_price_before_vat` = 1,`gdn_details`.`unit_price`,`gdn_details`.`unit_price` / (1 + `taxes`.`amount`)) * `gdn_details`.`quantity` * `taxes`.`amount` - if(`companies`.`is_price_before_vat` = 1,`gdn_details`.`unit_price`,`gdn_details`.`unit_price` / (1 + `taxes`.`amount`)) * `gdn_details`.`quantity` * `taxes`.`amount` * (`gdn_details`.`discount` / 100)) AS `line_tax` from (((((((`gdn_details` join `gdns` on(`gdns`.`id` = `gdn_details`.`gdn_id` and `gdns`.`deleted_at` is null and `gdns`.`cancelled_by` is null)) join `companies` on(`gdns`.`company_id` = `companies`.`id`)) join `products` on(`gdn_details`.`product_id` = `products`.`id`)) join `product_categories` on(`products`.`product_category_id` = `product_categories`.`id`)) join `taxes` on(`products`.`tax_id` = `taxes`.`id`)) join `warehouses` on(`gdn_details`.`warehouse_id` = `warehouses`.`id` and `warehouses`.`is_active` = 1)) left join `brands` on(`products`.`brand_id` = `brands`.`id`)) where `gdn_details`.`deleted_at` is null */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `gdn_master_reports`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `gdn_master_reports` AS select `gdns`.`id` AS `id`,`gdns`.`company_id` AS `company_id`,`gdns`.`created_by` AS `created_by`,`users`.`name` AS `user_name`,`gdns`.`warehouse_id` AS `warehouse_id`,`gdns`.`warehouse_id` AS `branch_id`,`warehouses`.`name` AS `branch_name`,`gdns`.`code` AS `code`,`gdns`.`customer_id` AS `customer_id`,`customers`.`company_name` AS `customer_name`,`customers`.`address` AS `customer_address`,(select min(`gdns_two`.`issued_on`) from `gdns` `gdns_two` where `gdns_two`.`customer_id` = `gdns`.`customer_id` and `gdns_two`.`deleted_at` is null) AS `customer_created_at`,`gdns`.`payment_type` AS `payment_type`,`gdns`.`bank_name` AS `bank_name`,`gdns`.`reference_number` AS `reference_number`,`gdns`.`cash_received_type` AS `cash_received_type`,`gdns`.`cash_received` AS `cash_received`,`gdns`.`discount` AS `discount`,`gdns`.`issued_on` AS `issued_on`,if(`gdns`.`discount` is null,(select sum(`gdn_detail_reports`.`line_price_before_tax`) from `gdn_detail_reports` where `gdn_detail_reports`.`gdn_id` = `gdns`.`id`),(select sum(`gdn_detail_reports`.`line_price_before_tax`) from `gdn_detail_reports` where `gdn_detail_reports`.`gdn_id` = `gdns`.`id`) - (select sum(`gdn_detail_reports`.`line_price_before_tax`) from `gdn_detail_reports` where `gdn_detail_reports`.`gdn_id` = `gdns`.`id`) * (`gdns`.`discount` / 100)) AS `subtotal_price`,if(`gdns`.`discount` is null,(select sum(`gdn_detail_reports`.`line_tax`) from `gdn_detail_reports` where `gdn_detail_reports`.`gdn_id` = `gdns`.`id`),(select sum(`gdn_detail_reports`.`line_tax`) from `gdn_detail_reports` where `gdn_detail_reports`.`gdn_id` = `gdns`.`id`) - (select sum(`gdn_detail_reports`.`line_tax`) from `gdn_detail_reports` where `gdn_detail_reports`.`gdn_id` = `gdns`.`id`) * (`gdns`.`discount` / 100)) AS `total_tax`,`credits`.`credit_amount` AS `credit_amount`,`credits`.`credit_amount_settled` AS `credit_amount_settled`,`credits`.`credit_amount` - `credits`.`credit_amount_settled` AS `credit_amount_unsettled`,`credits`.`last_settled_at` AS `last_settled_at` from (((((`gdns` join `warehouses` on(`gdns`.`warehouse_id` = `warehouses`.`id` and `warehouses`.`is_active` = 1)) left join `users` on(`gdns`.`created_by` = `users`.`id`)) left join `customers` on(`gdns`.`customer_id` = `customers`.`id`)) left join `credits` on(`gdns`.`id` = `credits`.`creditable_id` and `credits`.`creditable_type` = 'App\\Models\\Gdn')) join `companies` on(`gdns`.`company_id` = `companies`.`id`)) where `gdns`.`deleted_at` is null and `gdns`.`cancelled_by` is null and `gdns`.`subtracted_by` is not null */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `sale_detail_reports`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sale_detail_reports` AS select `sale_details`.`sale_id` AS `sale_id`,`sale_details`.`sale_id` AS `master_id`,`sale_details`.`sale_id` AS `sale_master_report_id`,`product_categories`.`id` AS `product_category_id`,`product_categories`.`name` AS `product_category_name`,`sale_details`.`product_id` AS `product_id`,`products`.`name` AS `product_name`,`products`.`code` AS `product_code`,`products`.`unit_of_measurement` AS `product_unit_of_measurement`,`sale_details`.`quantity` AS `quantity`,`sale_details`.`unit_price` AS `unit_price`,if(`products`.`is_product_single` = 1,(select `inventory_valuation_histories`.`unit_cost` from `inventory_valuation_histories` where `inventory_valuation_histories`.`product_id` = `sale_details`.`product_id` and `inventory_valuation_histories`.`type` = `products`.`inventory_valuation_method` and `inventory_valuation_histories`.`created_at` <= `sales`.`issued_on` and `inventory_valuation_histories`.`deleted_at` is null order by `inventory_valuation_histories`.`id` desc limit 1),(select sum(`inventory_valuation_histories`.`unit_cost` * `product_bundles`.`quantity`) from (`product_bundles` join `inventory_valuation_histories` on(`product_bundles`.`component_id` = `inventory_valuation_histories`.`product_id`)) where `product_bundles`.`product_id` = `sale_details`.`product_id` and `inventory_valuation_histories`.`type` = `products`.`inventory_valuation_method` and `inventory_valuation_histories`.`created_at` <= `sales`.`issued_on` and `inventory_valuation_histories`.`deleted_at` is null group by `product_bundles`.`product_id` order by `inventory_valuation_histories`.`id` desc)) AS `unit_cost`,`brands`.`name` AS `brand_name`,if(`companies`.`is_price_before_vat` = 1,`sale_details`.`unit_price`,`sale_details`.`unit_price` / (1 + `taxes`.`amount`)) * `sale_details`.`quantity` AS `line_price_before_tax`,if(`companies`.`is_price_before_vat` = 1,`sale_details`.`unit_price`,`sale_details`.`unit_price` / (1 + `taxes`.`amount`)) * `sale_details`.`quantity` * `taxes`.`amount` AS `line_tax` from ((((((`sale_details` join `sales` on(`sales`.`id` = `sale_details`.`sale_id` and `sales`.`deleted_at` is null and `sales`.`cancelled_by` is null)) join `companies` on(`sales`.`company_id` = `companies`.`id`)) join `products` on(`sale_details`.`product_id` = `products`.`id`)) join `product_categories` on(`products`.`product_category_id` = `product_categories`.`id`)) join `taxes` on(`products`.`tax_id` = `taxes`.`id`)) left join `brands` on(`products`.`brand_id` = `brands`.`id`)) where `sale_details`.`deleted_at` is null */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `sale_master_reports`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sale_master_reports` AS select `sales`.`id` AS `id`,`sales`.`company_id` AS `company_id`,`sales`.`created_by` AS `created_by`,`users`.`name` AS `user_name`,`sales`.`warehouse_id` AS `warehouse_id`,`sales`.`warehouse_id` AS `branch_id`,`warehouses`.`name` AS `branch_name`,`sales`.`code` AS `code`,`sales`.`customer_id` AS `customer_id`,`customers`.`company_name` AS `customer_name`,`customers`.`address` AS `customer_address`,(select min(`sales_two`.`issued_on`) from `sales` `sales_two` where `sales_two`.`customer_id` = `sales`.`customer_id` and `sales_two`.`deleted_at` is null and `sales_two`.`cancelled_by` is null) AS `customer_created_at`,`sales`.`fs_number` AS `fs_number`,`sales`.`payment_type` AS `payment_type`,`sales`.`bank_name` AS `bank_name`,`sales`.`reference_number` AS `reference_number`,`sales`.`cash_received_type` AS `cash_received_type`,`sales`.`cash_received` AS `cash_received`,`sales`.`issued_on` AS `issued_on`,(select sum(`sale_detail_reports`.`line_price_before_tax`) from `sale_detail_reports` where `sale_detail_reports`.`sale_id` = `sales`.`id`) AS `subtotal_price`,(select sum(`sale_detail_reports`.`line_tax`) from `sale_detail_reports` where `sale_detail_reports`.`sale_id` = `sales`.`id`) AS `total_tax`,`credits`.`credit_amount` AS `credit_amount`,`credits`.`credit_amount_settled` AS `credit_amount_settled`,`credits`.`credit_amount` - `credits`.`credit_amount_settled` AS `credit_amount_unsettled`,`credits`.`last_settled_at` AS `last_settled_at` from (((((`sales` join `warehouses` on(`sales`.`warehouse_id` = `warehouses`.`id` and `warehouses`.`is_active` = 1)) left join `users` on(`sales`.`created_by` = `users`.`id`)) left join `customers` on(`sales`.`customer_id` = `customers`.`id`)) left join `credits` on(`sales`.`id` = `credits`.`creditable_id` and `credits`.`creditable_type` = 'App\\Models\\Sale')) join `companies` on(`sales`.`company_id` = `companies`.`id`)) where `sales`.`deleted_at` is null and `sales`.`cancelled_by` is null and if(`companies`.`can_sale_subtract` = 1,`sales`.`subtracted_by` is not null,`sales`.`approved_by` is not null) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2020_10_28_070207_core_v1',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2021_02_21_184516_create_permission_tables',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2022_02_28_085540_create_pads_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2022_04_05_115551_create_transactions_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (111,'2022_07_04_132903_add_is_converted_to_sales_to_gdns_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (112,'2022_07_06_092530_add_closed_by_to_jobs',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (113,'2022_07_11_201759_create_employee_transfers_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (114,'2022_07_11_202414_create_employee_transfer_details_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (115,'2022_07_11_143138_departments',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (116,'2022_07_11_144506_add_hr_fields_to_employees_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (117,'2022_07_16_105354_create_warnings_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (118,'2022_07_19_085501_attendances',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (119,'2022_07_19_090110_attendance_details',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (120,'2022_07_21_085604_create_advancements_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (121,'2022_07_21_085636_create_advancement_details_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (122,'2022_07_21_121852_add_features_to_pads',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (123,'2022_07_23_083944_add_approvabel_column_to_bill_of_materials_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (124,'2022_07_20_164146_create_leave_category_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (125,'2022_07_20_164230_create_leave_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (126,'2022_07_25_135943_create_expense_claims_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (127,'2022_07_25_140222_create_expense_claim_details_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (128,'2022_07_25_084349_create_earning_categories_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (129,'2022_07_26_084855_create_earnings_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (130,'2022_07_26_122108_create_earning_details_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (131,'2022_07_28_110333_add_income_tax_region_to_companies_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (132,'2022_07_28_094947_create_announcements_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (133,'2022_07_28_101340_create_announcement_warehouse_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (134,'2022_07_28_113540_add_time_period_column_to_attendances_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (135,'2022_07_29_174533_drop_column_type_from_leave_categories_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (136,'2022_07_30_132407_change_tin_to_unique',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (137,'2022_08_01_101835_create_compensations_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (138,'2022_08_02_154724_create_employee_compensations_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (139,'2022_08_05_115943_make_uniques_starting_period_and_ending_period_t_o_attendances_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (140,'2022_08_05_141219_drop_gross_salary_from_employees_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (141,'2022_08_02_144548_create_pad_statuses_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (142,'2022_08_05_203944_update_transaction_field_data',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (143,'2022_08_03_142603_create_compensation_adjustments_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (144,'2022_08_03_142705_create_compensation_adjustment_details_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (145,'2022_08_09_150824_create_push_subscriptions_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (146,'2022_08_16_083027_drop_discount_column',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (147,'2022_08_16_090206_addbatch_field_to_products_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (148,'2022_08_16_140212_add_batch_and_expiry_date_column_to_grn_details_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (149,'2022_08_17_081512_add_columns_to_purchase_tables',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (150,'2022_08_17_081529_add_columns_to_purchase_details_tables',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (151,'2022_08_17_122505_add_columns_to_companies',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (152,'2022_08_17_142514_create_merchandise_batches_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (153,'2022_08_18_101858_drop_earning_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (154,'2022_08_18_105601_create_employee_compensation_histories_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (155,'2022_08_18_111716_remove_field_form_employee_compensation_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (156,'2022_08_18_114515_remove_gross_salary_from_advancement_details_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (157,'2022_08_18_165847_add_paid_time_amount_and_type_and_working_days_to_companies_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (158,'2022_08_18_170009_add_paid_time_off_amount_to_employees_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (159,'2022_08_18_170346_add_is_paid_time_off_and_time_off_amount_to_leaves_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (160,'2022_08_19_000403_create_debts_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (161,'2022_08_19_000442_create_debt_settlements_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (162,'2022_08_19_122434_add_debt_limit_column_to_suppliers_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (163,'2022_08_19_153628_add_columns_to_purchase_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (164,'2022_08_23_120539_add_columns_to_companies_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (165,'2022_08_23_150006_create_expense_categories_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (166,'2022_08_23_150026_create_expenses_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (167,'2022_08_23_150040_create_expense_details_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (168,'2022_08_26_211828_add_columns_to_purchases_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (169,'2022_08_26_212832_drop_columns_from_purchase_details_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (172,'2022_08_30_120233_add_amount_column_to_purchase_details_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (173,'2022_09_01_160324_make_adjustment_in_purchases',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (174,'2022_08_29_085931_add_back_order_field_to_companies_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (175,'2022_08_29_093107_add_column_to_companies_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (177,'2022_09_23_104041_add_bank_and_ref_no_field_to_gdns_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (178,'2022_09_23_121127_add_bank_and_ref_no_field_to_sales_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (179,'2022_09_23_140608_add_bank_and_ref_no_field_to_reservations_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (180,'2022_09_30_154951_add_referenece_number_to_expenses_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (183,'2022_10_07_134931_create_price_increments_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (184,'2022_10_07_135451_create_price_increment_details_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (185,'2022_10_07_135953_create_contacts_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (186,'2022_10_10_074220_add_contact_column_to_sale_gdn_pi_reservation_purchase_expense_features',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (187,'2022_10_11_074552_add_can_check_inventory_on_forms_to_companies',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (188,'2022_10_25_113643_create_brands_table',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (189,'2022_10_25_135435_add_active_and_inactive_for_sale_purchase_and_job_to_products_table',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (190,'2022_10_25_154004_add_brands_column_on_products_table',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (191,'2022_10_26_105355_add_other_costs_column_on_purchase_table',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (192,'2022_08_22_145038_create_payrolls_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (193,'2022_11_02_104442_add_maximum_amount_to_compensations',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (194,'2022_11_02_151826_create_payroll_details_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (195,'2022_11_04_082246_create_job_detail_histories_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (196,'2022_11_04_160144_remove_warehouse_id_from_payrolls',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (197,'2022_11_06_202929_add_payroll_bank_account_to_companies',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (198,'2022_11_07_102444_create_compensation_for_all_companies',26);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (199,'2022_08_28_181500_create_sale_reports_view',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (200,'2022_08_28_205045_create_gdn_reports_view',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (203,'2022_11_09_084934_create_inventory_histories_table',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (204,'2022_11_10_112358_migrate_inventory_data_to_inventory_history_table',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (205,'2022_11_08_142931_update_column_property_on_bill_of_material_details_table',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (206,'2022_11_15_142628_add_paid_at_to_payrolls_table',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (207,'2022_12_11_004714_update_compensation_table',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (208,'2022_12_13_115808_update_value_in_transaction_fields',31);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (209,'2022_12_08_142650_create_taxes_table',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (210,'2022_12_08_142739_add_tax_column_to_products_table',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (211,'2022_12_26_143644_assign_tax_to_products',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (212,'2022_12_27_193506_modify_gdn_report_views',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (214,'2022_12_27_232811_add_column_on_compensation_adjustments_table',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (215,'2022_12_29_190034_update_days_in_attendance_table',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (216,'2022_12_26_115017_add_can_show_employee_job_title_column_on_companies_table',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (217,'2022_12_27_145108_add_columns_to_gdns_table',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (219,'2022_11_25_215726_add_notification_field_to_companies_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (220,'2022_11_25_221505_add_is_converted_to_damage_field_to_merchandise_batches_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (221,'2022_12_30_111529_add_column_to_gdn_details_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (222,'2023_01_12_145516_add_can_select_batch_number_on_forms_column_on_companies_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (223,'2023_01_17_151527_add_unit_cost_column_on_grn_details_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (224,'2023_01_19_212510_add_merchandise_batch_id_to_damage_details',36);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (225,'2023_01_19_215901_add_received_quantity_to_merchandise_batches',36);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (226,'2023_01_20_101408_add_columns_to_customer_and_supplier_tables',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (227,'2023_01_27_120555_add_filter_customer_and_supplier_column_on_companies_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (228,'2023_01_27_201309_add_merchandise_id_column_on_tables',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (229,'2023_01_29_211508_add_options_to_compensation_adjustments',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (230,'2023_01_30_083317_add_merchandise_batch_id_on_sales_table',39);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (231,'2023_01_31_170236_add_balance_field_to_customers_table',40);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (232,'2023_02_01_083857_create_customer_deposits_table',40);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (233,'2023_02_01_114305_drop_columns_from_prices_table',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (234,'2023_02_07_220733_add_and_modify_column_on_purchases_table',42);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (235,'2023_02_09_183315_remove_discount_from_pad_fields',43);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (236,'2023_02_14_141917_add_gdn_id_on_returns_table',44);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (237,'2023_02_17_154034_add_description_section_on__expense_table',44);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (238,'2023_02_08_095851_add_rejected_by_and_canceled_by_field_to_purchases_table',45);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (239,'2023_02_20_120846_add_is_freight_amount_by_volume_column_on_companies_table',46);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (240,'2023_02_21_135244_add_payment_method_option_columns_on_expenses_table',46);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (241,'2023_02_23_113348_update_unit_cost_in_grns',47);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (243,'2023_02_23_145200_adjust_amount_in_purchase_details',48);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (244,'2023_02_20_100244_add_returned_quantity_to_gdn_details_table',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (245,'2023_03_03_160423_modify_unit_price_in_expense_details',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (246,'2023_03_10_145248_fix_inventory_level_inaccuracy',51);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (247,'2023_03_16_131831_add_update_status_permission_to_pads',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (248,'2023_03_21_163621_drop_date_unique_to_compensation_adjustments_table',53);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (249,'2023_03_27_140039_drop_unique_constraint_from_attendances',53);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (250,'2023_03_28_153731_add_columns_to_companies',54);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (251,'2023_03_29_075057_add_working_days_to_payrolls',55);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (252,'2023_03_30_143657_add_description_to_leaves_table',56);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (255,'2023_02_06_094711_drop_tax_type_to_expenses_table',58);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (256,'2023_03_01_095844_add_tax_id_and_drop_tax_type_to_purchases_table',58);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (257,'2023_04_25_125420_add_with_withholding_to_sales',59);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (258,'2023_04_26_105005_add_return_type_to_companies',60);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (259,'2023_04_26_123020_change_has_withholding_to_nullable',60);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (260,'2023_04_27_125858_add_is_read_only_to_pad_fields',61);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (261,'2023_04_28_222752_create_batching_fields_for_existing_pads',62);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (262,'2023_05_01_180148_add_merchandise_batch_to_pads',63);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (263,'2023_05_02_112459_add_creditable_to_gdn',64);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (265,'2023_05_08_121423_create_product_reorders_table',65);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (266,'2023_07_18_072115_necessary_changes_for_subtractable_sale',66);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (267,'2019_12_14_000001_create_personal_access_tokens_table',67);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (268,'2023_08_17_171735_add_columns_for_pos_integration',67);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (269,'2023_08_27_153347_add_print_columns_to_pads',68);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (270,'2023_08_28_102526_create_custom_fields_table',69);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (271,'2023_08_29_163511_create_custom_field_values',69);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (273,'2023_09_05_224324_give_users_access_to_all_warehouses_for_transfer_source_branch_permission',70);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (274,'2023_09_14_110408_add_columns_to_inventory_histories_table',71);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (275,'2023_09_15_144733_fix_inventory_history_data',72);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (276,'2023_09_18_124406_add_auto_generated_credit_issued_on_date_to_companies',73);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (277,'2023_08_24_163126_add_inventory_valuation_fields_to_products_table',74);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (278,'2023_08_24_163503_create_inventory_valuation_balances_table',74);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (279,'2023_08_24_163548_create_inventory_valuation_histories_table',74);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (280,'2023_09_02_132725_add_original_quantity_to_inventory_valuation_balances_table',74);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (281,'2023_09_04_154022_create_cost_updates_table',74);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (282,'2023_09_04_160513_create_cost_update_details_table',74);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (283,'2023_09_18_165716_add_columns_to_inventory_valuation_related_tables',74);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (286,'2023_09_25_135246_change_sales_report_source_column_default_value_on_companies_table',75);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (287,'2022_12_27_193607_modify_sale_report_views',76);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (288,'2023_01_13_214744_filter_our_cancelled_gdns',76);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (289,'2023_09_29_101913_change_fs_number_to_string',77);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (290,'2023_10_03_215517_add_proforma_invoiceable_to_proforma_invoiceable_table',78);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (291,'2023_10_28_211917_add_can_show_product_code_on_printouts_on_companies',79);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (292,'2023_10_11_023104_add_is_product_single_column_to_products_table',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (293,'2023_10_11_203151_create_product_bundles_table',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (294,'2023_10_13_081408_add_product_id_on_inventory_history_unique_constraint',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (295,'2023_10_16_104954_add_can_siv_subtract_from_inventory_column_to_companies_table',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (296,'2023_10_16_165119_add_subtracted_by_to_sivs_table',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (297,'2023_10_16_194521_change_unique_index_on_valuation_related_tables',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (298,'2023_10_18_020533_add_merchandise_batch_id_to_siv_details_table',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (299,'2023_10_18_101047_add_sivable_to_sivs_table',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (300,'2023_10_22_163539_drop_columns_from_sivs',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (301,'2023_10_24_092200_add_delivered_quantity_to_sale_and_gdn_table',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (302,'2023_10_26_135433_add_is_partial_deliveries_enabled_to_companies_table',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (303,'2023_10_29_220724_assign_sivs_as_delivered',80);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (304,'2023_11_14_095505_add_batch_columns_to_purchase_details',81);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (305,'2023_11_17_233943_add_warehouse_id_to_custom_field_values',82);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (306,'2023_10_31_141059_create_exchanges_table',83);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (307,'2023_10_31_150602_create_exchange_details_table',83);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (308,'2023_11_01_141615_add_is_admin_to_users',83);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (309,'2023_11_14_152607_add_is_in_training_in_companies',83);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (310,'2023_11_18_224501_drop_has_payment_term_from_pads',83);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (311,'2023_11_22_115215_create_subscriptions_table',84);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (312,'2023_11_26_162403_add_has_withholding_to_reservations',85);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (313,'2023_12_04_120446_temp_create_company_subscriptions',86);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (314,'2023_11_28_080039_add_profit_margin_type_column_to_products_table',87);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (315,'2023_11_29_222503_add_can_sell_below_cost_column_to_companies_table',88);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (316,'2024_04_23_100945_add_does_receive_sales_report_email_to_employees',89);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (317,'2024_04_23_123945_create_jobs_table',89);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (318,'2024_04_24_094302_create_failed_jobs_table',90);
