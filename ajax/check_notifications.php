<?php
include ("../../../inc/includes.php");

// Verifica se o usuário está logado
Session::checkLoginUser();

// Obtém o ID do usuário logado
$users_id = Session::getLoginUserID();

// Log para depuração
error_log("Verificando notificações para o usuário: $users_id");

// Consulta para encontrar tickets atribuídos ao técnico com respostas não vistas (AJUSTADA)
$query = "SELECT DISTINCT
    t.id AS ticket_id,
    t.name AS ticket_name,
    t.content AS ticket_content,
    tf.id AS followup_id,
    tf.date AS followup_date,
    tf.content AS followup_content,
    u.id AS user_id,
    u.name AS user_name
FROM
    glpi_tickets t
JOIN
    glpi_tickets_users tu ON t.id = tu.tickets_id AND tu.type = 2 AND tu.users_id = $users_id
JOIN
    glpi_itilfollowups tf ON t.id = tf.items_id AND tf.itemtype = 'Ticket'
JOIN
    glpi_users u ON tf.users_id = u.id
LEFT JOIN
    glpi_plugin_ticketanswers_views v ON (t.id = v.tickets_id AND tf.id = v.ticketfollowups_id)
WHERE
    v.id IS NULL
    AND tf.users_id != tu.users_id
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
    )
ORDER BY
    tf.date DESC";

error_log("Query: $query");

$result = $DB->query($query);
$notifications = [];

if ($result) {
    $num_rows = $DB->numrows($result);
    error_log("Consulta executada com sucesso. Número de linhas: $num_rows");

    while ($data = $DB->fetchAssoc($result)) {
        $notifications[] = [
            'ticket_id' => $data['ticket_id'], // Ajustado para usar o alias
            'ticket_name' => $data['ticket_name'], // Ajustado para usar o alias
            'followup_id' => $data['followup_id'],
            'followup_date' => $data['followup_date'],
            'user_name' => $data['user_name'],
            'content' => html_entity_decode($data['followup_content']) // Ajustado para usar o alias
        ];
    }
} else {
    $error = $DB->error();
    error_log("Erro na consulta: $error");
}

// Retorna o resultado como JSON
header('Content-Type: application/json');
$response = [
    'count' => count($notifications),
    'notifications' => $notifications
];
error_log("Resposta: " . json_encode($response));
echo json_encode($response);
?>
