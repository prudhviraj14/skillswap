<?php 
$pageTitle = "Home";
include 'includes/header.php'; 
?>

<section class="hero">
    <h1>Learn to Code</h1>
    <p>Interactive tutorials and references for web development</p>
    <a href="/courses/" class="btn">Browse Courses</a>
</section>

<section class="featured-courses">
    <h2>Popular Courses</h2>
    <div class="course-grid">
        <?php
        $stmt = $pdo->query("SELECT * FROM courses LIMIT 4");
        while ($course = $stmt->fetch()) {
            echo '<div class="course-card">';
            echo '<h3>' . htmlspecialchars($course['title']) . '</h3>';
            echo '<p>' . htmlspecialchars(substr($course['description'], 0, 100)) . '...</p>';
            echo '<a href="/courses/view.php?id=' . $course['id'] . '" class="btn">Start Learning</a>';
            echo '</div>';
        }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>