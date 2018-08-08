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

include('../../../inc/includes.php');
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

// Make a select box
if (isset($_POST["itemtype"])
    && CommonITILObject::isPossibleToAssignType($_POST["itemtype"])) {
   $dbu   = new DbUtils();
   $table = $dbu->getTableForItemType($_POST["itemtype"]);
   $rand  = mt_rand();

   // Message for post-only
   if (!isset($_POST["admin"]) || ($_POST["admin"] == 0)) {
      echo __('Enter the first letters (user, item name, serial or asset number)');
   }
   echo "&nbsp";
   $field_id = Html::cleanId("dropdown_" . $_POST['myname'] . $rand);

   $p = ['itemtype'        => $_POST["itemtype"],
         'entity_restrict' => $_POST['entity_restrict'],
         'table'           => $table,
         'width'           => $_POST["width"],
         'myname'          => $_POST["myname"]];

   echo Html::jsAjaxDropdown($_POST['myname'], $field_id,
                             $CFG_GLPI['root_doc'] . "/ajax/getDropdownFindNum.php",
                             $p);
   // Auto update summary of active or just solved tickets
   $params = ['items_id' => '__VALUE__',
              'itemtype' => $_POST['itemtype']];

   Ajax::updateItemOnSelectEvent($field_id, "item_ticket_selection_information",
                                 $CFG_GLPI["root_doc"] . "/ajax/ticketiteminformation.php",
                                 $params);

}
