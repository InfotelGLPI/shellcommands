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
include ('../../../inc/includes.php');

Html::header(PluginShellcommandsShellcommand::getTypeName(2), '', "tools", "pluginshellcommandsshellcommand", "shellcommand");

$ma = $_SESSION["plugin_shellcommands"]["massiveaction"];
unset($_SESSION["plugin_shellcommands"]["massiveaction"]);

$ids = $_SESSION["plugin_shellcommands"]["ids"];
unset($_SESSION["plugin_shellcommands"]["ids"]);

// Launch massive action
$processor = getItemForItemtype($ma->processor);
$processor->doMassiveAction($ma, $ids);

Html::footer();
?>