<?php
// NUMERIC
if (is_numeric($score1) && is_numeric($score2)) {
	// calculating WIN-1 DRAW-0.5 or LOSS-0
	if ($score1 > $score2) {$result1 = 1; $result2 = 0;}
	if ($score1 == $score2) {$result1 = 0.5; $result2 = 0.5;}
	if ($score1 < $score2) {$result1 = 0; $result2 = 1;}
	// calculating diference FAKTOR - 400
	$diference1 = ($points2 - $points1) / 400;
	$diference2 = ($points1 - $points2) / 400;
	// TEAM 1 ELO
	$resultEXP1 = 1 / ( 1 + (pow(10, $diference1)));
	$newpoints1 = round($points1 + (100 * ($result1 - $resultEXP1)));
	$elo1 = $newpoints1 - $points1;
	// TEAM 2 ELO
	$resultEXP2 = 1 / ( 1 + (pow(10, $diference2)));
	$newpoints2 = round($points2 + (100 * ($result2 - $resultEXP2)));
	$elo2 = $newpoints2 - $points2;
}
// NOT NUMMERIC
if ($score1 == "canceled" && $score2 == "canceled") {$elo1 = '0'; $elo2 = '0'; $newpoints1 = $points1; $newpoints2 = $points2;}
if ($score1 == "noshow1" && $score2 == "noshow1") {$elo1 = '-10'; $elo2 = '10'; $newpoints1 = $points1 - '10'; $newpoints2 = $points2 + '10';}
if ($score1 == "noshow2" && $score2 == "noshow2") {$elo1 = '10'; $elo2 = '-10'; $newpoints1 = $points1 + '10'; $newpoints2 = $points2 - '10';}
?>