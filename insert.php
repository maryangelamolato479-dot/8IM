<?php
$conn = new mysqli("localhost", "root", "", "movie_dw");

$json = file_get_contents("movies.json");
$data = json_decode($json, true);

foreach ($data['movies'] as $movie) {
    // Insert Movie
    $stmt = $conn->prepare("INSERT INTO dim_movie (movie_id, title, language, country) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $movie['mov_id'], $movie['mov_title'], $movie['mov_lang'], $movie['mov_rel_country']);
    $stmt->execute();

    // Insert Director
    $dir = $movie['director'];
    $stmt = $conn->prepare("INSERT INTO dim_director (director_id, first_name, last_name) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $dir['id'], $dir['first_name'], $dir['last_name']);
    $stmt->execute();

    // Insert Actors
    foreach ($movie['actors'] as $actor) {
        $stmt = $conn->prepare("INSERT INTO dim_actor (actor_id, first_name, last_name, gender) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $actor['id'], $actor['first_name'], $actor['last_name'], $actor['gender']);
        $stmt->execute();
    }

    // Insert Date
    $date = $movie['mov_dt_rel'];
    $year = date("Y", strtotime($date));
    $month = date("m", strtotime($date));
    $day = date("d", strtotime($date));
    $stmt = $conn->prepare("INSERT INTO dim_date (release_date, year, month, day) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siii", $date, $year, $month, $day);
    $stmt->execute();
    $date_id = $conn->insert_id;

    // Insert Fact
    $stmt = $conn->prepare("INSERT INTO fact_movie_reviews (movie_id, director_id, actor_id, date_id, avg_stars, num_ratings) VALUES (?, ?, ?, ?, ?, ?)");
    $actor_id = $movie['actors'][0]['id']; // first actor for simplicity
    $stmt->bind_param("iiiidi", $movie['mov_id'], $dir['id'], $actor_id, $date_id, $movie['rating']['avg_stars'], $movie['rating']['num_ratings']);
    $stmt->execute();
}

echo "Data inserted successfully!";
?>
