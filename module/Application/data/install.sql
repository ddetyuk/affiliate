INSERT INTO `user_users` (`id`, `email`, `password`, `reset`, `referral`, `balance`, `rate`, `firstname`, `lastname`, `status`, `created`, `updated`) VALUES (1, 'admin@gmail.com', 'temp123', '', 0, 0, 0, NULL, NULL, 'active', NOW(), NOW()),

INSERT INTO `affiliate`.`account_settings` (`name` ,`value`)VALUES ('account_level1_percent', '0.1');
INSERT INTO `affiliate`.`account_settings` (`name` ,`value`)VALUES ('account_level2_percent', '0.05');
INSERT INTO `affiliate`.`account_settings` (`name` ,`value`)VALUES ('account_level3_percent', '0.05');
INSERT INTO `affiliate`.`account_settings` (`name` ,`value`)VALUES ('account_system_balance', '0');
INSERT INTO `affiliate`.`account_settings` (`name` ,`value`)VALUES ('account_users_balance', '0');