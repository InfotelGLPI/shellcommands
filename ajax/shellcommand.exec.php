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

include ("../../../inc/includes.php");

Session::checkLoginUser();

$command = new PluginShellcommandsShellcommand();
$command_item = new PluginShellcommandsShellcommand_Item();

$command->checkGlobal(READ);

header("Content-Type: text/html; charset=UTF-8");

switch ($_POST['command_type']) {
   case 'PluginShellcommandsShellcommand' :
      PluginShellcommandsShellcommand_Item::lauchCommand($_POST);
      break;
   case 'PluginShellcommandsCommandGroup' :
      PluginShellcommandsCommandGroup_Item::lauchCommand($_POST);
      break;
   case 'PluginShellcommandsAdvanced_Execution' :
      PluginShellcommandsAdvanced_Execution::lauchCommand($_POST);
      break;
}

?>