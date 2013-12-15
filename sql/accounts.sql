CREATE TABLE `accounts` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(128),
    `account_access` int(11),
    `passwordhash` char(107), -- scrypt
    `personas` text NOT NULL, -- 1|2|3|5|83, etc; used in REGEXP
    `settings` text NOT NULL,
    PRIMARY KEY(`id`),
    UNIQUE KEY(`username`),
    KEY(`account_access`)
);

CREATE TABLE `access_levels` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `label` VARCHAR(32),
    `type` VARCHAR(32),
    `value` int(11),
    PRIMARY KEY(`id`),
    KEY(`value`)
);
INSERT INTO `access_levels`
  ( `label`, `type`, `value` ) VALUES
  ( 'Banned', 'account', '0' );
INSERT INTO `access_levels`
  ( `label`, `type`, `value` ) VALUES
  ( 'User', 'account', '1' );
INSERT INTO `access_levels`
  ( `label`, `type`, `value` ) VALUES
  ( 'Moderator', 'account', '2' );
INSERT INTO `access_levels`
  ( `label`, `type`, `value` ) VALUES
  ( 'Staff', 'account', '3' );
