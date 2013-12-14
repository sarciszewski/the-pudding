CREATE TABLE `accounts` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(128),
    `passwordhash` varchar(128), -- scrypt
    `personas` text NOT NULL, -- 1|2|3|5|83, etc; used in REGEXP
    PRIMARY KEY(`id`),
    UNIQUE KEY(`username`)
);
