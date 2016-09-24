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

$group_item = new PluginShellcommandsCommandGroup_Item();

if (isset($_POST["add"])) {
   $group_item->check(-1, UPDATE, $_POST);
   $group_item->add($_POST);
   Html::back();
   
} else if (isset($_POST["up"]) || isset($_POST["up_x"])) {
   $group_item->orderItem($_POST,'up');
   Html::back();

} else if (isset($_POST["down"]) || isset($_POST["down_x"])) {
   $group_item->orderItem($_POST,'down');
   Html::back();
} 
?>
