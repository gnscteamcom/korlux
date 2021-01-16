SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

DROP TABLE IF EXISTS jastipdetails;

CREATE TABLE `jastipdetails` (
  `id` int(10) UNSIGNED NOT NULL,
  `jastip_id` int(10) NOT NULL,
  `product_name` text NOT NULL,
  `qty` int(10) NOT NULL,
  `harga_won` bigint(20) NOT NULL,
  `harga_rp` bigint(20) NOT NULL,
  `weight` int(10) NOT NULL,
  `product_link` text NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `jastipdetails`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `jastipdetails`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
