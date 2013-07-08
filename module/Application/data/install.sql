INSERT INTO `user_users` (`id`, `email`, `password`, `reset`, `referral`, `balance`, `rate`, `firstname`, `lastname`, `status`, `created`, `updated`) VALUES (1, 'admin@gmail.com', 'temp123', '', 0, 0, 0, NULL, NULL, 'active', NOW(), NOW()),

INSERT INTO `affiliate`.`account_settings` (`name` ,`value`)VALUES ('account_level1_percent', '0.1');
INSERT INTO `affiliate`.`account_settings` (`name` ,`value`)VALUES ('account_level2_percent', '0.05');
INSERT INTO `affiliate`.`account_settings` (`name` ,`value`)VALUES ('account_level3_percent', '0.05');
INSERT INTO `affiliate`.`account_settings` (`name` ,`value`)VALUES ('account_system_balance', '0');
INSERT INTO `affiliate`.`account_settings` (`name` ,`value`)VALUES ('account_users_balance', '0');
INSERT INTO `affiliate`.`account_settings` (`name` ,`value`)VALUES ('account_users_minval', '0.6');

INSERT INTO `account_products` (`id`, `minamount`, `maxamount`, `term`, `rate`, `daily`, `level1`, `level2`, `level3`, `created`, `updated`, `type`) VALUES
(1, '5.00', '100.00', '15', '1.450', '0.030', '0.050', '0.025', '0.010', NULL, NULL, 'short'),
(2, '101.00', '1000.00', '30', '2.050', '0.035', '0.060', '0.030', '0.014', NULL, NULL, 'short'),
(3, '1001.00', '10000.00', '60', '3.400', '0.040', '0.070', '0.035', '0.018', NULL, NULL, 'long'),
(4, '10001.00', '0.00', '120', '6.400', '0.045', '0.080', '0.040', '0.020', NULL, NULL, 'long');
