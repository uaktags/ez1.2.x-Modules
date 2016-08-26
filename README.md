# Simple Analytics
## Currently just a hook, but soon to be module

## This hook relies on specific database tables to be created. Until a module is built, these will have to be done manually.

```

--
-- Table structure for table `ezrpg_players_tracking`
--

CREATE TABLE IF NOT EXISTS `ezrpg_players_tracking` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `pid` int(6) NOT NULL DEFAULT '0',
  `tm` varchar(20) NOT NULL DEFAULT '',
  `ref` varchar(250) NOT NULL DEFAULT '',
  `agent` varchar(250) NOT NULL DEFAULT '',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `host_name` varchar(20) NOT NULL DEFAULT '',
  `tracking_page_name` varchar(10) NOT NULL DEFAULT '',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

```


