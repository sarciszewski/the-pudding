CREATE TABLE `accounts` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(128),
    `account_access` int(11),
    `passwordhash` char(107), -- scrypt
    `personas` text NOT NULL, -- 1|2|3|5|83, etc; used in REGEXP
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