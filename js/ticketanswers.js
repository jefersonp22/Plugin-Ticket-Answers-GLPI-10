/*
 -------------------------------------------------------------------------
 Ticket Answers
 Copyright (C) 2023 by Jeferson Penna Alves
 -------------------------------------------------------------------------
 LICENSE
 This file is part of Ticket Answers.
 Ticket Answers is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 Ticket Answers is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Ticket Answers. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
*/

window.GlpiPluginTicketAnswers = null;

class TicketAnswers {

   constructor() {
      this.init();
   }

   getNotificationButton() {
      return `
         <button type="button" class="notification-bell btn btn-outline-secondary" title="Notificações">
             <i class="fas fa-bell fa-lg"></i>
         </button>`;
   }

   injectNotificationButton(input_element, container = undefined) {
      if (input_element !== undefined && input_element.length > 0) {
         if (container !== undefined) {
            container.append(this.getNotificationButton());
         } else {
            input_element.after(this.getNotificationButton());
            container = input_element.parent();
         }

         container.find('.notification-bell').on('click', () => {
            // Redireciona para a página de notificações
            window.location.href = CFG_GLPI.root_doc + '/plugins/ticketanswers/front/index.php';
         });
      }
   }

   hookGlobalSearch() {
      const global_search = $('input[name="globalsearch"]');
      this.injectNotificationButton(global_search, global_search.closest('.input-group'));
   }

   checkNotifications() {
      console.log('Verificando notificações...');
      $.ajax({
         url: CFG_GLPI.root_doc + '/plugins/ticketanswers/ajax/check_notifications.php',
         type: 'GET',
         dataType: 'json',
         success: (data) => {
            console.log('Notificações verificadas:', data);
            if (data.count > 0) {
               // Há notificações não lidas
               $('.notification-bell i').addClass('has-notifications');
               
               // Adiciona um contador de notificações
               if ($('.notification-count').length === 0) {
                  $('.notification-bell').append('<span class="notification-count">' + data.count + '</span>');
               } else {
                  $('.notification-count').text(data.count);
               }
            } else {
               // Não há notificações não lidas
               $('.notification-bell i').removeClass('has-notifications');
               $('.notification-count').remove();
            }
         },
         error: (xhr, status, error) => {
            console.error('Erro ao verificar notificações:', error);
         }
      });
   }

   init() {
      console.log('Inicializando TicketAnswers...');
      // Adiciona o botão de notificação ao lado da caixa de pesquisa global
      this.hookGlobalSearch();
      
      // Verifica notificações imediatamente
      setTimeout(() => this.checkNotifications(), 2000);
      
      // Verifica notificações a cada 5 segundos (para testes)
      setInterval(() => this.checkNotifications(), 5000);
   }
}

$(document).ready(() => {
   console.log('Document ready, criando TicketAnswers...');
   window.GlpiPluginTicketAnswers = new TicketAnswers();
});
