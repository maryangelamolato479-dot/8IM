<?php
$conn = new mysqli("localhost", "root", "", "movie_dw");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch movie, director, actor, release date, ratings
$sql = "SELECT m.title, d.first_name AS dir_fname, d.last_name AS dir_lname,
               a.first_name AS act_fname, a.last_name AS act_lname,
               dt.release_date, f.avg_stars, f.num_ratings
        FROM fact_movie_reviews f
        JOIN dim_movie m ON f.movie_id = m.movie_id
        JOIN dim_director d ON f.director_id = d.director_id
        JOIN dim_actor a ON f.actor_id = a.actor_id
        JOIN dim_date dt ON f.date_id = dt.date_id";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Movie Data Warehouse Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .btn { display: inline-block; padding: 10px 15px; margin: 10px; background: #007BFF; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>🎬 Movie Data Warehouse Dashboard</h1>

    <!-- Buttons to trigger insert and display -->
    <div style="text-align:center;">
        <a href="insert.php" class="btn">Insert JSON Data</a>
        <a href="display.php" class="btn">View Display Page</a>
    </div>

    <!-- Inline display of results -->
    <h2>Movie Reviews Summary</h2>
    <table>
        <tr>
            <th>Movie</th>
            <th>Director</th>
            <th>Actor</th>
            <th>Release Date</th>
            <th>Avg Stars</th>
            <th>Total Ratings</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
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
        } else {
            echo "<tr><td colspan='6'>No data available. Please insert JSON first.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
<?php
$conn->close();
?>
