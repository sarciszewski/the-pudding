-- DRAFT
-- Events: Things that are happening; e.g. "Protests in NL on Dec 20, 2013"
-- Providers: Ustream, justin.tv, etc.
-- Channels: Actual video feeds related to a particular event
-- ^- Make sense to everyone?

CREATE TABLE `stream_events` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `location` text NOT NULL,
    `created` datetime,
    `modified` datetime,
    PRIMARY KEY(`id`)
);

CREATE TABLE `stream_providers` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(32) NOT NULL, -- Companies: You only get 32 chars >:]
    `created` datetime,
    `modified` datetime,
    PRIMARY KEY(`id`)
);

CREATE TABLE `stream_channel` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `event` int(11) unsigned NOT NULL,
    `account` int(11) unsigned NOT NULL,
    -- `persona` int(11) unsigned, -- Optional: Allow a persona access (??)
    `provider` int(11) unsigned,
    `stream_id` varchar(128) NOT NULL,
    `live` tinyint(1) unsigned DEFAULT 0,
    PRIMARY KEY(`id`),
    KEY(`event`),
    KEY(`account`),
    KEY(`provider`)
);