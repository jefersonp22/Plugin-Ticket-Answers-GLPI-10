<?php
include ("../../../inc/includes.php");

Session::checkRight("config", READ);

Html::header("Ticket Answers", $_SERVER['PHP_SELF'], "plugins", "pluginticketanswersmenu", "config");

echo "<div class='center'>";
echo "<h1>" . __("Configuração do Ticket Answers", "ticketanswers") . "</h1>";
// Seu código de configuração aqui
echo "</div>";

Html::footer();
