<?php
include ("../../../inc/includes.php");

// Verifica se o usuário está logado
Session::checkLoginUser();

// Obtém o ID do usuário logado
$users_id = Session::getLoginUserID();

// Log para depuração
error_log("Marcando todas as notificações como lidas para o usuário: $users_id");

// Consulta para encontrar todas as notificações não lidas (AJUSTADA)
$query = "SELECT DISTINCT
    t.id AS ticket_id,
    tf.id AS followup_id
FROM
    glpi_tickets t
JOIN
    glpi_tickets_users tu ON t.id = tu.tickets_id AND tu.type = 2 AND tu.users_id = $users_id
JOIN
    glpi_itilfollowups tf ON t.id = tf.items_id AND tf.itemtype = 'Ticket'
LEFT JOIN
    glpi_plugin_ticketanswers_views v ON (t.id = v.tickets_id AND tf.id = v.ticketfollowups_id)
WHERE
    v.id IS NULL
    AND tf.users_id != $users_id
    AND t.status != 6  -- Filtro para chamados não fechados (status 6)
    AND tf.date > (
        SELECT
            COALESCE(MAX(tf2.date), '1970-01-01')
        FROM
            glpi_itilfollowups tf2
        WHERE
            tf2.items_id = t.id
            AND tf2.itemtype = 'Ticket'
            AND tf2.users_id = $users_id
    )";

error_log("Query: $query");

$result = $DB->query($query);
$count = 0;

if ($result && $DB->numrows($result) > 0) {
    while ($data = $DB->fetchAssoc($result)) {
        // Insere na tabela de visualizações
        $insertResult = $DB->insert('glpi_plugin_ticketanswers_views', [
            'tickets_id' => $data['ticket_id'], // Ajustado para usar o alias
            'users_id' => $users_id,
            'ticketfollowups_id' => $data['followup_id'],
            'viewed_at' => date('Y-m-d H:i:s')
        ]);
        if($insertResult){
            $count++;
            error_log("Registro inserido na tabela de visualizações");
        } else {
            $error = $DB->error();
            error_log("Erro ao inserir registro na tabela de visualizações: $error");
        }
    }

    // Log para depuração
    error_log("$count notificações marcadas como lidas");
} else {
    $error = $DB->error();
    error_log("Erro na consulta ou nenhum resultado encontrado: $error");
}

// Redireciona de volta para a página de notificações
header('Location: ' . $CFG_GLPI["root_doc"] . '/plugins/ticketanswers/front/index.php');
?>
