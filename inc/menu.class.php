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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginShellcommandsMenu
 *
 * This class shows the plugin main page
 *
 * @package    Shellcommands
 * @author     Ludovic Dupont
 */
class PluginShellcommandsMenu extends CommonDBTM {

   static $rightname = 'plugin_shellcommands';

   static function getTypeName($nb = 0) {
      return __('Shellcommands menu', 'shellcommands');
   }

   static function canView() {
      return Session::haveRight(self::$rightname, READ);
   }

   static function canCreate() {
      return Session::haveRightsOr(self::$rightname, [CREATE, UPDATE, DELETE]);
   }

   /**
    * Show config menu
    */
   function showMenu() {
      global $CFG_GLPI;

      if (!$this->canView()) {
         return false;
      }

      echo "<div align='center'>";
      echo "<table class='tab_cadre' cellpadding='5' height='150'>";
      echo "<tr>";
      echo "<th colspan='5'>" . PluginShellcommandsShellcommand::getTypeName(2) . "</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_1' style='background-color:white;'>";

      // Add shell command
      echo "<td class='center shellcommands_menu_item'>";
      echo "<a  class='shellcommands_menu_a' href=\"./shellcommand.php\">";
      echo "<i class='fas fa-terminal fa-5x' style='color:#b5b5b5' title=\"" . PluginShellcommandsShellcommand::getTypeName(2) . "\"></i>";
      echo "<br><br>" . PluginShellcommandsShellcommand::getTypeName(2) . "</a>";
      echo "</td>";

      // Command group
      echo "<td class='center shellcommands_menu_item'>";
      echo "<a  class='shellcommands_menu_a' href=\"./commandgroup.php\">";
      echo "<i class='far fa-list-alt fa-5x' style='color:#b5b5b5' title=\"" . PluginShellcommandsCommandGroup::getTypeName(2) . "\"></i>";
      echo "<br><br>" . PluginShellcommandsCommandGroup::getTypeName(2) . "</a>";
      echo "</td>";

      // Advanced execution
      echo "<td class='center shellcommands_menu_item'>";
      echo "<a  class='shellcommands_menu_a' href=\"./advanced_execution.php\">";
      echo "<i class='fas fa-angle-double-right fa-5x' style='color:#b5b5b5' title=\"" . PluginShellcommandsAdvanced_Execution::getTypeName(2) . "\"></i>";
      echo "<br><br>" . PluginShellcommandsAdvanced_Execution::getTypeName(2) . "</a>";
      echo "</td>";

      echo "</table></div>";
   }
}
