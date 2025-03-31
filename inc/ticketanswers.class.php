<?php

class PluginTicketanswers extends CommonGLPI {
   static $rightname = 'plugin_ticketanswers';

   static function getTypeName($nb = 0) {
      return __('Ticket Answers', 'ticketanswers');
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      if ($item->getType() == 'Ticket') {
         return self::getTypeName();
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
   
   function getForbiddenStandardMassiveAction() {
      return [];
   }
   
   static function getMenuShorcut() {
      return 't';
   }
   
   // Este método é crucial para o menu principal
   static function getMenuContent() {
      global $CFG_GLPI;
      
      $menu = [];
      $menu['title'] = self::getTypeName(2);
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
}
