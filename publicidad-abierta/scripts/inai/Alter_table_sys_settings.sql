
ALTER TABLE sys_settings ADD COLUMN `clave` text AFTER `recaptcha`;
ALTER TABLE sys_settings ADD COLUMN `tipo` varchar(50)  AFTER `clave`;
ALTER TABLE sys_settings ADD COLUMN `active` int  AFTER `tipo`;
