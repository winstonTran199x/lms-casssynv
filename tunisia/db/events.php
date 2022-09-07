<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
defined('MOODLE_INTERNAL') || die();
$observers = array(
	array(
		'eventname' => 'core\event\user_updated',
        'callback' => '\local_tunisia\user_event_handler::add_user_status_when_updated',
	),
    array(
		'eventname' => 'core\event\user_created',
        'callback' => '\local_tunisia\user_event_handler::add_user_status_when_created',
	),
//    array(
//		'eventname' => 'core\event\user_deleted',
//        'callback' => 'auto_add_user_handler::add_user_logs',
//	),
);
