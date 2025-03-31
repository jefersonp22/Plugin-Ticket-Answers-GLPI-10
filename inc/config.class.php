<?php

class PluginTicketanswersConfig extends CommonDBTM {
    
   static protected $notable = true;
   
   /**
    * @see CommonGLPI::getMenuName()
   **/
   static function getMenuName() {
      return __('Ticket Answers', 'ticketanswers');
   }
   
   /**
    *  @see CommonGLPI::getMenuContent()
   **/
   static function getMenuContent() {
      global $CFG_GLPI;
   
      $menu = array();

      $menu['title'] = __('Ticket Answers', 'ticketanswers');
      $menu['page']  = '/plugins/ticketanswers/front/index.php';
      $menu['icon']  = 'fas fa-bell'; // Ícone de sino
      
      return $menu;
   }
}
?>
