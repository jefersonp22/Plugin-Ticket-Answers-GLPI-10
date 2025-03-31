<?php

class PluginTicketanswers extends CommonGLPI {
   static $rightname = 'plugin_ticketanswers';

   static function getMenuName() {
      return __("Ticket Answers", "ticketanswers");
   }

   static function getMenuContent() {
      global $CFG_GLPI;
      
      $menu = [];
      $menu['title'] = self::getMenuName();
      $menu['page'] = "/plugins/ticketanswers/front/menu.php";
      $menu['icon'] = "fas fa-bell"; // Ícone de sino
      
      // Adicionar submenus se necessário
      $menu['options'] = [
         'config' => [
            'title' => __('Configuração', 'ticketanswers'),
            'page'  => '/plugins/ticketanswers/front/config.php',
            'icon'  => 'fas fa-cog',
         ],
         'stats' => [
            'title' => __('Estatísticas', 'ticketanswers'),
            'page'  => '/plugins/ticketanswers/front/stats.php',
            'icon'  => 'fas fa-chart-bar',
         ],
      ];
      
      return $menu;
   }
   
   // Adicione estes métodos para a funcionalidade de aba em tickets
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      if ($item->getType() == 'Ticket') {
         return __('Ticket Answers', 'ticketanswers');
      }
      return '';
   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      if ($item->getType() == 'Ticket') {
         // Código para exibir o conteúdo da aba
         echo "<div class='center'>";
         echo "<h3>" . __('Ticket Answers', 'ticketanswers') . "</h3>";
         // Seu código aqui
         echo "</div>";
      }
      return true;
   }
}
