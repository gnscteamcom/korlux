SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

DROP TABLE IF EXISTS jastips;

CREATE TABLE `jastips` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) NOT NULL,
  `invoicenumber` text NOT NULL,
  `total_weight` int(10) NOT NULL,
  `shipment_cost` bigint(20) NOT NULL,
  `unique_nominal` int(10) NOT NULL,
  `grand_total` bigint(20) NOT NULL,
  `total_paid` bigint(20) NOT NULL,
  `total_dp` bigint(20) NOT NULL DEFAULT 0,
  `total_pelunasan` bigint(20) NOT NULL DEFAULT 0,
  `customeraddress_id` int(10) NOT NULL,
  `payment_date` date NULL DEFAULT NULL,
  `shipment_date` date NULL DEFAULT NULL,
  `ordered_date` date NULL DEFAULT NULL,
  `is_lunas` tinyint(4) NOT NULL DEFAULT 0,
  `has_ordered` tinyint(4) NOT NULL DEFAULT 0,
  `ordered_by` int(10) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `jastips`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `jastips`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
