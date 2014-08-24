<?php
// ENT ELO System
// INPUTS: points1 - points2 - score1 - score2
// OUTPUTS: elo1 - elo2 - newpoints1 - newpoints2
if ($ratingsystem == "elo") {
	// NUMERIC
	if (is_numeric($score1) && is_numeric($score2)) {
		$diference = abs($points1 - $points2); // DIFEREBCE IN POINTS
	
	}
	// NOT NUMMERIC
	if ($score1 == "canceled" && $score2 == "canceled") {$elo1 = '0'; $elo2 = '0'; $newpoints1 = $points1; $newpoints2 = $points2;}
	if ($score1 == "noshow1" && $score2 == "noshow1") {$elo1 = '-10'; $elo2 = '10'; $newpoints1 = $points1 -10; $newpoints2 = $points2 +10;}
	if ($score1 == "noshow2" && $score2 == "noshow2") {$elo1 = '10'; $elo2 = '-10'; $newpoints1 = $points1 +10; $newpoints2 = $points2 -10;}
}
?>