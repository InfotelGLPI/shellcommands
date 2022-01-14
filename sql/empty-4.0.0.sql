DROP TABLE IF EXISTS `glpi_plugin_shellcommands_shellcommands`;
CREATE TABLE `glpi_plugin_shellcommands_shellcommands` (
   `id` int unsigned NOT NULL auto_increment,
   `entities_id` int unsigned NOT NULL default '0',
   `is_recursive` tinyint NOT NULL default '0',
   `name` varchar(255) collate utf8mb4_unicode_ci default NULL,
   `link` varchar(255) collate utf8mb4_unicode_ci default NULL,
   `plugin_shellcommands_shellcommandpaths_id` int unsigned NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_shellcommands_shellcommandpaths (id)',
   `parameters` varchar(255) collate utf8mb4_unicode_ci default NULL,
   `is_deleted` tinyint NOT NULL default '0',
        `tag_position` tinyint NOT NULL default '1',
   PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

INSERT INTO `glpi_plugin_shellcommands_shellcommands` VALUES (1,0,1, 'Ping', '[IP]', '1','-c 2',0,1);
INSERT INTO `glpi_plugin_shellcommands_shellcommands` VALUES (2,0,1, 'Tracert', '[NAME]', '2','',0,1);
INSERT INTO `glpi_plugin_shellcommands_shellcommands` VALUES (3,0,1, 'Wake on Lan', '[MAC]', '0','',0,1);
INSERT INTO `glpi_plugin_shellcommands_shellcommands` VALUES (4,0,1, 'Nslookup', '[DOMAIN]', '3','',0,1);

DROP TABLE IF EXISTS `glpi_plugin_shellcommands_shellcommandpaths`;
CREATE TABLE `glpi_plugin_shellcommands_shellcommandpaths` (
  `id` int unsigned NOT NULL auto_increment,
  `name` varchar(255) collate utf8mb4_unicode_ci NOT NULL,
  `comment` text collate utf8mb4_unicode_ci,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

INSERT INTO `glpi_plugin_shellcommands_shellcommandpaths` (`ID`,`name`) VALUES
(1, '/bin/ping'),
(2, '/usr/bin/traceroute'),
(3,'/usr/bin/nslookup'),
(4, 'c:\\windows\\system32\\ping.exe'),
(5, 'c:\\windows\\system32\\tracert.exe'),
(6, 'c:\\windows\\system32\\nslookup.exe');

DROP TABLE IF EXISTS `glpi_plugin_shellcommands_shellcommands_items`;
CREATE TABLE `glpi_plugin_shellcommands_shellcommands_items` (
   `id` int unsigned NOT NULL auto_increment,
   `plugin_shellcommands_shellcommands_id` int unsigned NOT NULL default '0',
   `itemtype` varchar(100) collate utf8mb4_unicode_ci NOT NULL COMMENT 'see .class.php file',
   PRIMARY KEY  (`id`),
   UNIQUE KEY `FK_cmd` (`plugin_shellcommands_shellcommands_id`,`itemtype`),
   KEY `itemtype` (`itemtype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `glpi_plugin_shellcommands_profiles`;
CREATE TABLE `glpi_plugin_shellcommands_profiles` (
  `id` int unsigned NOT NULL auto_increment,
  `profiles_id` int unsigned NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
  `shellcommands` char(1) collate utf8mb4_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `profiles_id` (`profiles_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginShellcommandsShellcommand','2','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginShellcommandsShellcommand','3','3','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginShellcommandsShellcommand','4','4','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginShellcommandsShellcommand','5','5','0');

DROP TABLE IF EXISTS `glpi_plugin_shellcommands_commandgroups_items`;
CREATE TABLE `glpi_plugin_shellcommands_commandgroups_items` (
   `id` int unsigned NOT NULL auto_increment,
   `plugin_shellcommands_shellcommands_id` int unsigned NOT NULL default '0',
        `plugin_shellcommands_commandgroups_id` int unsigned NOT NULL default '0',
        `rank` int unsigned NOT NULL default '0',
   PRIMARY KEY  (`id`),
   UNIQUE KEY `FK_cmd` (`plugin_shellcommands_shellcommands_id`,`plugin_shellcommands_commandgroups_id`),
   KEY `plugin_shellcommands_commandgroups_id` (`plugin_shellcommands_commandgroups_id`),
        KEY `plugin_shellcommands_shellcommands_id` (`plugin_shellcommands_shellcommands_id`),
        KEY `rank` (`rank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `glpi_plugin_shellcommands_commandgroups`;
CREATE TABLE `glpi_plugin_shellcommands_commandgroups` (
   `id` int unsigned NOT NULL auto_increment,
   `name` varchar(255) collate utf8mb4_unicode_ci NOT NULL,
        `check_commands_id` int unsigned NOT NULL default '0',
        `entities_id` int unsigned NOT NULL default '0',
   `is_recursive` tinyint NOT NULL default '0',
   PRIMARY KEY  (`id`),
        KEY `entities_id` (`entities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
