<?php

namespace local_tunisia;

use core\event\user_created;
use core\event\user_updated;
use core_calendar\local\event\entities\event;
use dml_exception;

class user_event_handler {

    public static function add_user_status_when_updated(user_updated $event){
        self::process_add_user_status($event);
    }

    public static function add_user_status_when_created(user_created $event){
        self::process_add_user_status($event);
    }

    static function process_add_user_status($event){
        global $DB;
        $user = $event->get_record_snapshot('user', $event->objectid);
        if ($user){
            $user->idnumber = $user->id;
            $user->action = $event->get_data()['crud'];
            $user->status = 'NOT_SYNC';
            try {
                $user->id = $DB->insert_record('local_tunisia_user_changes', $user);
            } catch (dml_exception $e) {
                self::write_sync_errors_logs($e);
                throw $e;
            }
        }
    }

    public static function write_sync_errors_logs($log_msg){
        $log_filename = $_SERVER['DOCUMENT_ROOT']."/log";
        if (!file_exists($log_filename))
        {
            // create directory/folder uploads.
            mkdir($log_filename, 0777, true);
        }
        $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
        file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
    }
}