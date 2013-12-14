CREATE TABLE `personas` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `admins` varchar(255),    -- People who can add/remove persona access
   `accounts` text NOT NULL, -- People who can use this persona
   `username` varchar(255) NOT NULL,
   `bio` text NOT NULL,
    `gpgkey` text, -- Optional but highly recommended
    PRIMARY KEY(`id`),
    KEY(`username`)
);
