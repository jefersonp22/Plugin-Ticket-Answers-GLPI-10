<?php

class PluginTicketanswersDebug {
    
    static function log($message) {
        $log_file = GLPI_LOG_DIR . '/ticketanswers.log';
        
        if (is_array($message) || is_object($message)) {
            $message = json_encode($message);
        }
        
        $date = date('Y-m-d H:i:s');
        file_put_contents($log_file, "[$date] $message\n", FILE_APPEND);
    }
}
