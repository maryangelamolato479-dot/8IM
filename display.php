<?php
$conn = new mysqli("localhost", "root", "", "movie_dw");

$sql = "SELECT m.title, d.first_name AS dir_fname, d.last_name AS dir_lname,
               a.first_name AS act_fname, a.last_name AS act_lname,
               dt.release_date, f.avg_stars, f.num_ratings
        FROM fact_movie_reviews f
        JOIN dim_movie m ON f.movie_id = m.movie_id
        JOIN dim_director d ON f.director_id = d.director_id
        JOIN dim_actor a ON f.actor_id = a.actor_id
        JOIN dim_date dt ON f.date_id = dt.date_id";

$result = $conn->query($sql);

echo "<table border='1'>
<tr><th>Movie</th><th>Director</th><th>Actor</th><th>Release Date</th><th>Avg Stars</th><th>Total Ratings</th></tr>";

while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['dir_fname']} {$row['dir_lname']}</td>
            <td>{$row['act_fname']} {$row['act_lname']}</td>
            <td>{$row['release_date']}</td>
            <td>{$row['avg_stars']}</td>
            <td>{$row['num_ratings']}</td>
          </tr>";
}

echo "</table>";

$conn->close();
?>
