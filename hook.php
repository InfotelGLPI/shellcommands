<?php
/*
 * @version $Id: HEADER 15930 2011-10-30 15:47:55Z tsmr $
 -------------------------------------------------------------------------
 shellcommands plugin for GLPI
 Copyright (C) 2009-2016 by the shellcommands Development Team.

 https://github.com/InfotelGLPI/shellcommands
 -------------------------------------------------------------------------

 LICENSE

 This file is part of shellcommands.

 shellcommands is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 shellcommands is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with shellcommands. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

function plugin_shellcommands_install() {
   global $DB;

   include_once(GLPI_ROOT . "/plugins/shellcommands/inc/profile.class.php");

   $update = false;
   if (!$DB->tableExists("glpi_plugin_cmd_profiles") && !$DB->tableExists("glpi_plugin_shellcommands_shellcommands")) {
      $DB->runFile(GLPI_ROOT . "/plugins/shellcommands/sql/empty-2.2.0.sql");

   } else if ($DB->tableExists("glpi_plugin_cmd_profiles") && !$DB->tableExists("glpi_plugin_cmd_path")) {
      $update = true;
      $DB->runFile(GLPI_ROOT . "/plugins/shellcommands/sql/update-1.1.sql");
      $DB->runFile(GLPI_ROOT . "/plugins/shellcommands/sql/update-1.2.0.sql");
      $DB->runFile(GLPI_ROOT . "/plugins/shellcommands/sql/update-1.3.0.sql");

   } else if ($DB->tableExists("glpi_plugin_cmd_profiles") && $DB->fieldExists("glpi_plugin_cmd_profiles", "interface")) {
      $update = true;
      $DB->runFile(GLPI_ROOT . "/plugins/shellcommands/sql/update-1.2.0.sql");
      $DB->runFile(GLPI_ROOT . "/plugins/shellcommands/sql/update-1.3.0.sql");

   } else if (!$DB->tableExists("glpi_plugin_shellcommands_shellcommands")) {
      $update = true;
      $DB->runFile(GLPI_ROOT . "/plugins/shellcommands/sql/update-1.3.0.sql");

   } else if (!$DB->tableExists("glpi_plugin_shellcommands_commandgroups")) {
      $DB->runFile(GLPI_ROOT . "/plugins/shellcommands/sql/update-1.7.0.sql");
   }

   if ($update) {
      $query_  = "SELECT *
            FROM `glpi_plugin_shellcommands_profiles` ";
      $result_ = $DB->query($query_);
      if ($DB->numrows($result_) > 0) {

         while ($data = $DB->fetch_array($result_)) {
            $query  = "UPDATE `glpi_plugin_shellcommands_profiles`
                  SET `profiles_id` = '" . $data["id"] . "'
                  WHERE `id` = '" . $data["id"] . "';";
            $DB->query($query);
         }
      }

      $query  = "ALTER TABLE `glpi_plugin_shellcommands_profiles`
               DROP `name` ;";
      $DB->query($query);
   }

   PluginShellcommandsProfile::initProfile();
   PluginShellcommandsProfile::createfirstAccess($_SESSION['glpiactiveprofile']['id']);
   $migration = new Migration("1.9.1");
   $migration->dropTable('glpi_plugin_shellcommands_profiles');

   return true;
}

function plugin_shellcommands_uninstall() {
   global $DB;

   $tables = ["glpi_plugin_shellcommands_shellcommands",
              "glpi_plugin_shellcommands_shellcommands_items",
              "glpi_plugin_shellcommands_commandgroups",
              "glpi_plugin_shellcommands_commandgroups_items",
              "glpi_plugin_shellcommands_profiles",
              "glpi_plugin_shellcommands_shellcommandpaths"];

   foreach ($tables as $table) {
      $DB->query("DROP TABLE IF EXISTS `$table`;");
   }

   //old versions
   $tables = ["glpi_plugin_cmd",
              "glpi_plugin_cmd_device",
              "glpi_plugin_cmd_setup",
              "glpi_plugin_cmd_profiles",
              "glpi_plugin_cmd_path"];

   foreach ($tables as $table) {
      $DB->query("DROP TABLE IF EXISTS `$table`;");
   }

   $tables_glpi = ["glpi_displaypreferences",
                   "glpi_savedsearches"];

   foreach ($tables_glpi as $table_glpi) {
      $DB->query("DELETE FROM `$table_glpi` WHERE `itemtype` = 'PluginShellcommandsShellcommand';");
   }

   include_once(GLPI_ROOT . "/plugins/shellcommands/inc/profile.class.php");

   PluginShellcommandsProfile::removeRightsFromSession();
   PluginShellcommandsProfile::removeRightsFromDB();

   return true;
}

// Define dropdown relations
function plugin_shellcommands_getDatabaseRelations() {

   $plugin = new Plugin();

   if ($plugin->isActivated("shellcommands")) {
      return ["glpi_plugin_shellcommands_shellcommandpaths" => ["glpi_plugin_shellcommands_shellcommands" => "plugin_shellcommands_shellcommandpaths_id"],
              "glpi_profiles"                               => ["glpi_plugin_shellcommands_profiles" => "profiles_id"],
              "glpi_plugin_shellcommands_shellcommands"     => ["glpi_plugin_shellcommands_shellcommands_items" => "plugin_shellcommands_shellcommands_id"],
              "glpi_plugin_shellcommands_shellcommands"     => ["glpi_plugin_shellcommands_commandgroups_items" => "plugin_shellcommands_shellcommands_id"],
              "glpi_plugin_shellcommands_commandgroups"     => ["glpi_plugin_shellcommands_commandgroups_items" => "plugin_shellcommands_commandgroups_id"],
              "glpi_entities"                               => ["glpi_plugin_shellcommands_shellcommands" => "entities_id"]];
   } else {
      return [];
   }
}

// Define Dropdown tables to be manage in GLPI :
function plugin_shellcommands_getDropdown() {
   $plugin = new Plugin();

   if ($plugin->isActivated("shellcommands")) {
      return ['PluginShellcommandsShellcommandPath' => __('Path', 'shellcommands')];
   } else {
      return [];
   }
}

function plugin_shellcommands_postinit() {

   foreach (PluginShellcommandsShellcommand::getTypes(true) as $type) {
      CommonGLPI::registerStandardTab($type, 'PluginShellcommandsShellcommand_Item');
   }
}

/**
 * Register methods for Webservices plugin (if available)
 *
 * @return void
 */
function plugin_shellcommands_registerWebservicesMethods() {
   global $WEBSERVICES_METHOD;

   $plugin = new Plugin();
   if ($plugin->isActivated('webservices')) { // If webservices plugin is active
      $WEBSERVICES_METHOD['shellcommands.list'] = ['PluginShellcommandsWebservice', 'methodList'];
      $WEBSERVICES_METHOD['shellcommands.run']  = ['PluginShellcommandsWebservice', 'methodRun'];
   }
}


//display custom fields in the search
function plugin_shellcommands_giveItem($type, $ID, $data, $num) {
   global $DB;

   $searchopt = &Search::getOptions($type);
   $table     = $searchopt[$ID]["table"];
   $field     = $searchopt[$ID]["field"];

   switch ($table . '.' . $field) {
      //display associated items with shellcommands
      case "glpi_plugin_shellcommands_shellcommands_items.itemtype" :
         $query_device  = "SELECT DISTINCT `itemtype`
                             FROM `glpi_plugin_shellcommands_shellcommands_items`
                             WHERE `plugin_shellcommands_shellcommands_id` = '" . $data['id'] . "'
                             ORDER BY `itemtype`";
         $result_device = $DB->query($query_device);
         $number_device = $DB->numrows($result_device);
         $out           = '';
         if ($number_device > 0) {
            for ($i = 0; $i < $number_device; $i++) {
               $itemtype = $DB->result($result_device, $i, "itemtype");
               $item     = new $itemtype();
               if (!class_exists($itemtype)) {
                  continue;
               }
               $out .= $item->getTypeName() . "<br>";
            }
         }
         return $out;
         break;
   }
   return "";
}

//force groupby for multible links to items
function plugin_shellcommands_forceGroupBy($type) {

   return true;
   switch ($type) {
      case 'PluginShellcommandsShellcommand':
         return true;
         break;
   }
   return false;
}

function plugin_shellcommands_MassiveActions($type) {

   $plugin = new Plugin();
   if ($plugin->isActivated("shellcommands")) {
      if (in_array($type, PluginShellcommandsShellcommand::getTypes(true))) {
         return [
            'PluginShellcommandsShellcommand' . MassiveAction::CLASS_ACTION_SEPARATOR . "generate" => __('Command launch', 'shellcommands'),
            'PluginShellcommandsCommandGroup' . MassiveAction::CLASS_ACTION_SEPARATOR . "generate" => __('Command group launch', 'shellcommands')
         ];
      }
   }
   return [];
}

