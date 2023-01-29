TYPE=TRIGGERS
triggers='CREATE DEFINER=`root`@`localhost` TRIGGER `audit_company_update` BEFORE UPDATE ON `users_company` FOR EACH ROW BEGIN\n	declare schema_name varchar(500);\n	\n	SELECT DATABASE() INTO schema_name FROM DUAL;\n	\n	UPDATE sprout_users.users_companies\n	SET \n	company_image_url = NEW.image_url,\n	company_name = NEW.company_name\n	WHERE database_name = schema_name;\n	\nEND'
sql_modes=524288
definers='root@localhost'
client_cs_names='utf8mb4'
connection_cl_names='utf8mb4_general_ci'
db_cl_names='latin1_swedish_ci'
