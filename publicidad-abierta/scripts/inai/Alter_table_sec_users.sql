--
-- Modificaciones en la tabla `sec_users`
--

ALTER TABLE sec_users ADD COLUMN `rol_user` text NOT NULL AFTER `last_update`;

/* Modificacion en columna active */
ALTER TABLE sec_users MODIFY active varchar(1) NOT NULL;


UPDATE `sec_users` SET `rol_user` = '1' WHERE `sec_users`.`id_user` = 1;
UPDATE `sec_users` SET `rol_user` = '2' WHERE `sec_users`.`id_user` = 2;




