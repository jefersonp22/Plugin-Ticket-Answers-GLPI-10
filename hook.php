<?php
/**
 * Hook file for Ticket Answers plugin
 */

function plugin_ticketanswers_install() {
   global $DB;
   
   // Cria tabela para rastrear respostas vistas
   if (!$DB->tableExists('glpi_plugin_ticketanswers_views')) {
      $query = "CREATE TABLE `glpi_plugin_ticketanswers_views` (
                  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `tickets_id` int(11) UNSIGNED NOT NULL,
                  `users_id` int(11) UNSIGNED NOT NULL,
                  `ticketfollowups_id` int(11) UNSIGNED NOT NULL,
                  `viewed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `ticket_user_followup` (`tickets_id`, `users_id`, `ticketfollowups_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
      $DB->query($query) or die("Error creating glpi_plugin_ticketanswers_views table");
   }
   
   // Instala os perfis
   PluginTicketanswersProfile::install(new Migration(PLUGIN_TICKETANSWERS_VERSION));
   return true;
}

function plugin_ticketanswers_uninstall() {
   global $DB;
   
   // Remove a tabela
   if ($DB->tableExists('glpi_plugin_ticketanswers_views')) {
      $DB->query("DROP TABLE `glpi_plugin_ticketanswers_views`");
   }
   
   // Remove os perfis
   PluginTicketanswersProfile::uninstall();
   return true;
}
?>
