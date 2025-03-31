<?php
include ("../../../inc/includes.php");

Session::checkLoginUser();

Html::header("Ticket Answers", $_SERVER['PHP_SELF'], "plugins", "PluginTicketanswersMenu");

echo "<div class='center'>";
echo "<h1>" . __("Ticket Answers", "ticketanswers") . "</h1>";

// Obtém o ID do usuário logado
$users_id = Session::getLoginUserID();

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
    glpi_plugin_ticketanswers_views v ON (t.id = v.tickets_id AND tf.id = v.ticketfollowups_id AND v.users_id = $users_id)
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

$result = $DB->query($query);
$numNotifications = $DB->numrows($result);

echo "<div id='ticket-notifications'>";
echo "<h2>" . __("Notificações", "ticketanswers") . "</h2>";

if ($result && $numNotifications > 0) {
    // Adiciona o botão "Marcar todos como lido" no topo da tabela
    echo "<div class='center' style='margin-bottom: 15px;'>";
    echo "<a href='../ajax/mark_all_as_read.php' class='btn btn-warning'>
            <i class='fas fa-check-double'></i> " . __("Marcar todos como lido", "ticketanswers") . "
          </a>";
    echo "</div>";
    
    echo "<table class='tab_cadre_fixehov'>";
    echo "<tr><th>" . __("Ticket") . "</th><th>" . __("Resposta de") . "</th><th>" . __("Data") . "</th><th>" . __("Conteúdo") . "</th><th>" . __("Ações") . "</th></tr>";
    
    while ($data = $DB->fetchAssoc($result)) {
        echo "<tr class='tab_bg_1'>";
        echo "<td><a href='" . $CFG_GLPI["root_doc"] . "/front/ticket.form.php?id=" . $data['ticket_id'] . "'>" . $data['ticket_name'] . "</a></td>"; // Ajustado para usar o alias
        echo "<td>" . $data['user_name'] . "</td>";
        echo "<td>" . Html::convDateTime($data['followup_date']) . "</td>"; // Ajustado para usar o alias
        echo "<td>" . html_entity_decode($data['followup_content']) . "</td>"; // Ajustado para usar o alias
        echo "<td>
            <a href='../ajax/mark_as_read.php?tickets_id=" . $data['ticket_id'] . "&followups_id=" . $data['followup_id'] . "&redirect_to=" . urlencode($CFG_GLPI["root_doc"] . "/front/ticket.form.php?id=" . $data['ticket_id']) . "' class='btn btn-primary'>
                <i class='fas fa-eye'></i> " . __("Ver chamado", "ticketanswers") . "
            </a>
        </td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>" . __("Não há notificações no momento.", "ticketanswers") . "</p>";
}

echo "</div>";
echo "</div>";

Html::footer();
?>
