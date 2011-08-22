SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;

-- Création de la table "users"
CREATE TABLE `users` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`firstName` VARCHAR( 70 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`lastName` VARCHAR( 70 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

UPDATE `application` SET `value` = '0002' WHERE `key` = 'lastSqlScriptRun';
SET FOREIGN_KEY_CHECKS=1;
COMMIT;