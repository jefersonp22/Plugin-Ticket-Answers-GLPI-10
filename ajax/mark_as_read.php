<?php
include ("../../../inc/includes.php");

// Verifica se o usuário está logado
Session::checkLoginUser();

// Obtém o ID do usuário logado
$users_id = Session::getLoginUserID();

// Obtém os IDs do ticket e do followup
$tickets_id = $_GET['tickets_id'] ?? 0;
$followups_id = $_GET['followups_id'] ?? 0;
$redirect_to = $_GET['redirect_to'] ?? $_SERVER['HTTP_REFERER'];

// Log para depuração
error_log("Marcando como lido: ticket=$tickets_id, followup=$followups_id, user=$users_id");

if ($tickets_id > 0 && $followups_id > 0) {
    // Insere na tabela de visualizações
    $insertResult = $DB->insert('glpi_plugin_ticketanswers_views', [
        'tickets_id' => $tickets_id,
        'users_id' => $users_id,
        'ticketfollowups_id' => $followups_id,
        'viewed_at' => date('Y-m-d H:i:s')
    ]);
    if($insertResult){
        error_log("Registro inserido na tabela de visualizações");
    } else {
        $error = $DB->error();
        error_log("Erro ao inserir registro na tabela de visualizações: $error");
    }
}

// Redireciona para a URL especificada ou para a página anterior
header('Location: ' . $redirect_to);
?>
