<?php
namespace local_tunisia\task;
use dml_exception;
use core\task\scheduled_task;
use local_tunisia\user_event_handler;

class sync_user_info_task extends scheduled_task{
    public function get_name(){
        return get_string('update_user_sync', 'local_tunisia');
    }

    public function execute(){
        if (!true) {
            // start sync user and upadte status
            self::update_user_sync_status('NOT_SYNC', 'SYNCING');
        } else {
            // upadte status after sync
            self::update_user_sync_status('SYNCING', 'SYNCED');
        }
    }

    public static function checking_user_and_upadte_status($user){
        global $CFG,$DB;
        require_once($CFG->dirroot . '/user/lib.php');
        if (!is_object($user)) {
            $user = (object) $user;
        }
        $get_user_record = $DB->get_record('user', array('username'=>$user->username));
        if(!$user->comfirmed){
            $user->comfirmed = '1';
        } if (!$user->auth) {
            $user->auth = 'db';
        } if (!$user->mnethostid) {
            $user->mnethostid = '1';
        } if (!$user->comfirmed) {
            $user->comfirmed = '1';
        }
        if (!$get_user_record){
            try {
                user_create_user($user, false);
            } catch (\moodle_exception $e) {
                user_event_handler::write_sync_errors_logs($e);
                throw $e;
            }
        } else {
            $new_obj_user = (object) array_intersect_key((array) $user, (array) $get_user_record);
            if (!$new_obj_user->id) {
                $new_obj_user->id = $get_user_record->id;
            }
            try {
                user_update_user($new_obj_user, false);
            } catch (\moodle_exception $e) {
                user_event_handler::write_sync_errors_logs($e);
                throw $e;
            }
        }
    }

    public static function update_user_sync_status($old_status,$new_status){
        global $DB;
        $users = $DB->get_records('local_tunisia_user_changes', array('status'=>$old_status));
        if ($users) {
            foreach ($users as $user){
                $user->status = $new_status;
                // do something with kafka here with user
                try {
                $DB->update_record('local_tunisia_user_changes', $user);
                } catch (dml_exception $e) {
                    user_event_handler::write_sync_errors_logs($e);
                    throw $e;
                }
            }
        }
    }
}