<?php
// Connexion à la base de données
require '../php/Sample/core/connection.php';

// Vérifier la connexion
if ($mysqli->connect_error) {
    die("Connexion échouée : " . $mysqli->connect_error);
}

// Définir le mois et l'année à afficher
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = intval($_GET['month']);
    $year = intval($_GET['year']);
} else {
    $month = date('n');
    $year = date('Y');
}

// Prendre en compte le changement de mois
if ($month < 1) {
    $month = 12;
    $year--;
} elseif ($month > 12) {
    $month = 1;
    $year++;
}

// Calculer les jours du mois
$first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
$total_days = date('t', $first_day_of_month);
$month_name = date('F', $first_day_of_month);
$day_of_week = date('N', $first_day_of_month);

// Précédent et suivant mois et année
$prev_month = $month - 1;
$next_month = $month + 1;
$prev_year = $year;
$next_year = $year;

if ($prev_month < 1) {
    $prev_month = 12;
    $prev_year--;
}
if ($next_month > 12) {
    $next_month = 1;
    $next_year++;
}

// Récupérer les événements du mois courant
$events = [];
$sql = "SELECT * FROM events WHERE MONTH(event_date) = $month AND YEAR(event_date) = $year";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[$row['event_date']] = $row['event_title'];
    }
}

// Afficher le calendrier
echo "<header>";
echo "<a href='?month=$prev_month&year=$prev_year'>&lt;</a>";
echo "<h2>$month_name $year</h2>";
echo "<a href='?month=$next_month&year=$next_year'>&gt;</a>";
echo "</header>";

echo "<table>";
echo "<tr>
        <th>Lun</th>
        <th>Mar</th>
        <th>Mer</th>
        <th>Jeu</th>
        <th>Ven</th>
        <th>Sam</th>
        <th>Dim</th>
      </tr>";

$current_day = 1;

echo "<tr>";
for ($i = 1; $i < $day_of_week; $i++) {
    echo "<td></td>";
}

while ($current_day <= $total_days) {
    if ($day_of_week > 7) {
        $day_of_week = 1;
        echo "</tr><tr>";
    }
    
    $date_class = "";
    $current_date = "$year-$month-".str_pad($current_day, 2, '0', STR_PAD_LEFT);
    if ($current_day == date('j') && $month == date('n') && $year == date('Y')) {
        $date_class = "style='background-color: #007bff; color: #fff;'";
    }

    if (isset($events[$current_date])) {
        $date_class = "style='background-color: #ffc107; color: #000;'";
        echo "<td $date_class onclick=\"showEventDetails('" . htmlspecialchars($events[$current_date]) . "')\">$current_day<br><small>" . htmlspecialchars($events[$current_date]) . "</small></td>";
    } else {
        echo "<td $date_class>$current_day</td>";
    }
    
    $current_day++;
    $day_of_week++;
}

while ($day_of_week <= 7) {
    echo "<td></td>";
    $day_of_week++;
}

echo "</tr>";
echo "</table>";

// Fermer la connexion
$mysqli->close();
?>
