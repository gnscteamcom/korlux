SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

DROP TABLE IF EXISTS resellerconfigs;

CREATE TABLE `resellerconfigs` (
  `id` int(10) UNSIGNED NOT NULL,
  `silver_upgrade_days` int(11) NOT NULL,
  `silver_downgrade_days` int(11) NOT NULL,
  `silver_min_upgrade` bigint(20) NOT NULL,
  `silver_min_downgrade` bigint(20) NOT NULL,
  `gold_upgrade_days` int(11) NOT NULL,
  `gold_downgrade_days` int(11) NOT NULL,
  `gold_min_upgrade` bigint(20) NOT NULL,
  `gold_min_downgrade` bigint(20) NOT NULL,
  `platinum_upgrade_days` int(11) NOT NULL,
  `platinum_downgrade_days` int(11) NOT NULL,
  `platinum_min_upgrade` bigint(20) NOT NULL,
  `platinum_min_downgrade` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `resellerconfigs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `resellerconfigs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

  INSERT INTO `resellerconfigs` (`id`, `silver_upgrade_days`, `silver_downgrade_days`, `silver_min_upgrade`, `silver_min_downgrade`, `gold_upgrade_days`, `gold_downgrade_days`, `gold_min_upgrade`, `gold_min_downgrade`, `platinum_upgrade_days`, `platinum_downgrade_days`, `platinum_min_upgrade`, `platinum_min_downgrade`, `created_at`, `updated_at`, `deleted_at`) VALUES
  (1, 60, 60, 2000000, 0, 60, 60, 5000000, 2000000, 60, 60, 20000000, 50000000, now(), now(), NULL);
