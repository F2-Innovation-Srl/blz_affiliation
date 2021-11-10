<?php
namespace BLZ\AdminUserInterface;

class AdminNotice
{
    const NOTICE_FIELD = 'blz-message';
    const NOTICE_LEVELS = ['notice-error','notice-warning','notice-info','notice-success'];
    
    public function displayAdminNotice()
    {

        foreach(self::NOTICE_LEVELS as $noticeLevel){
            
            $option      = get_option(self::NOTICE_FIELD."-".$noticeLevel);
            $message     = isset($option['message']) ? $option['message'] : false;
            if ($message) {
                echo "<div class='notice {$noticeLevel} is-dismissible'><p>{$message}</p></div>";
                delete_option(self::NOTICE_FIELD."-".$noticeLevel);
            }
        }
       
    }

    public static function addError($message)
    {
        self::updateOption($message, self::NOTICE_LEVELS[0]);
    }

    public static function addWarning($message)
    {
        self::updateOption($message, self::NOTICE_LEVELS[1]);
    }

    public static function addInfo($message)
    {
        self::updateOption($message, self::NOTICE_LEVELS[2]);
    }

    public static function addSuccess($message)
    {
        self::updateOption($message, self::NOTICE_LEVELS[3]);
    }

    protected static function updateOption($message, $noticeLevel) {
        update_option(self::NOTICE_FIELD."-".$noticeLevel, [
            'message' => $message,
            'notice-level' => $noticeLevel
        ]);
    }
}