CREATE TABLE `uploads` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `account` int(11) unsigned,
    `persona` int(11) unsigned,
    `filename` varchar(64) NOT NULL,
    `directory` varchar(64),
    `uploaded` datetime,
    `checksum` char(40) NOT NULL, -- To prevent duplication
    PRIMARY KEY (`id`),
    KEY(`account`),
    KEY(`persona`),
    UNIQUE KEY(`directory`, `filename`)
);