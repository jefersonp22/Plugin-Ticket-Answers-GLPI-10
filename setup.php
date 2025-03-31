<?php
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

define('PLUGIN_TICKETANSWERS_VERSION', '1.0.0');

/**
 * Nome e versão do plugin - Necessário
 */
function plugin_version_ticketanswers() {
    return array(
        'name'           => "Ticket Answers",
        'version'        => PLUGIN_TICKETANSWERS_VERSION,
        'author'         => 'Jeferson Penna Alves',
        'license'        => 'GPLv2+',
        'homepage'       => '',
        'minGlpiVersion' => '10.0.0',
        'maxGlpiVersion' => '10.0.17'
    );
}

/**
 * Inicialização dos hooks do plugin - Necessário
 **/
function plugin_init_ticketanswers() {
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['ticketanswers'] = true;

    // Adiciona o JavaScript e CSS
    $PLUGIN_HOOKS['add_javascript']['ticketanswers'][] = 'js/ticketanswers.js';
    $PLUGIN_HOOKS['add_css']['ticketanswers'][] = 'css/ticketanswers.css';

    // Mantém o menu no menu Plugins para acesso às configurações
    $PLUGIN_HOOKS["menu_toadd"]['ticketanswers'] = array('plugins' => 'PluginTicketanswersConfig');
    $PLUGIN_HOOKS['config_page']['ticketanswers'] = 'front/index.php';

    // Registra a classe principal
    Plugin::registerClass('PluginTicketanswers', array('addtabon' => array('Ticket')));
    
    // Registra a classe de perfil
    Plugin::registerClass('PluginTicketanswersProfile', 
                         ['addtabon' => ['Profile']]);
                         
    // Registra a classe de depuração
    include_once(GLPI_ROOT . '/plugins/ticketanswers/inc/debug.class.php');
}

/**
 * Verifica se a configuração está ok - Necessário
 */
function plugin_ticketanswers_check_config() {
    return true;
}

/**
 * Verifica se os pré-requisitos do plugin foram satisfeitos - Necessário
 */
function plugin_ticketanswers_check_prerequisites() {
    // Verifica se a versão do GLPI é compatível
    if (version_compare(GLPI_VERSION, '10.0.0', 'lt') || version_compare(GLPI_VERSION, '10.0.17', 'gt')) {
        echo "Este plugin requer GLPI >= 10.0.0 e GLPI <= 10.0.17";
        return false;
    }
    return true;
}

// Remova ou comente as funções abaixo
// function plugin_ticketanswers_install() {
//     return true;
// }

// function plugin_ticketanswers_uninstall() {
//     return true;
// }

?>
