<?php

class PluginTicketanswersProfile extends Profile {
   static function getTypeName($nb = 0) {
      return __('Ticket Answers', 'ticketanswers');
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      if ($item->getType() == 'Profile') {
         return self::getTypeName();
      }
      return '';
   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      if ($item->getType() == 'Profile') {
         $profile = new self();
         $profile->showForm($item->getID());
      }
      return true;
   }

   function showForm($profiles_id = 0, $openform = true, $closeform = true) {
      echo "<div class='firstbloc'>";
      if (($canedit = Session::haveRightsOr(self::$rightname, [CREATE, UPDATE, PURGE]))
          && $openform) {
         $profile = new Profile();
         echo "<form method='post' action='".$profile->getFormURL()."'>";
      }

      $profile = new Profile();
      $profile->getFromDB($profiles_id);

      $rights = [
         ['rights'    => [READ => __('Read')],
          'label'     => __('Ticket Answers', 'ticketanswers'),
          'field'     => 'plugin_ticketanswers'
         ]
      ];
      
      $matrix_options = ['canedit'       => $canedit,
                          'default_class' => 'tab_bg_2'];
      
      $profile->displayRightsChoiceMatrix($rights, $matrix_options);

      if ($canedit && $closeform) {
         echo "<div class='center'>";
         echo Html::hidden('id', ['value' => $profiles_id]);
         echo Html::submit(_sx('button', 'Save'), ['name' => 'update']);
         echo "</div>\n";
         Html::closeForm();
      }
      echo "</div>";
   }

   static function install($migration) {
      global $DB;
      
      // Adicionar permissão padrão para perfis existentes
      $profiles = $DB->request([
         'SELECT' => ['id'],
         'FROM'   => 'glpi_profiles'
      ]);
      
      foreach ($profiles as $profile) {
         $DB->updateOrInsert('glpi_profilerights', [
            'profiles_id'  => $profile['id'],
            'name'         => 'plugin_ticketanswers',
            'rights'       => READ
         ], [
            'profiles_id'  => $profile['id'],
            'name'         => 'plugin_ticketanswers'
         ]);
      }
   }

   static function uninstall() {
      global $DB;
      
      $DB->delete('glpi_profilerights', [
         'name' => 'plugin_ticketanswers'
      ]);
   }
}
