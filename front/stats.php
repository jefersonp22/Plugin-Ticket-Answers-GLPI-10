<?php
include ("../../../inc/includes.php");

Session::checkLoginUser();

Html::header("Ticket Answers", $_SERVER['PHP_SELF'], "plugins", "pluginticketanswersmenu", "stats");

echo "<div class='center'>";
echo "<h1>" . __("Estatísticas do Ticket Answers", "ticketanswers") . "</h1>";
// Seu código de estatísticas aqui
echo "</div>";

Html::footer();
