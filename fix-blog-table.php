<?php
// fix-blog-table.php
include 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "<h3>Fixing Blog Posts Table</h3>";

try {
    // First, let's check the current table structure
    echo "Checking current table structure...<br>";
    $table_info = $db->query("DESCRIBE blog_posts")->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($table_info)) {
        echo "Current table structure:<br>";
        echo "<pre>";
        foreach ($table_info as $column) {
            echo "{$column['Field']} - {$column['Type']}<br>";
        }
        echo "</pre>";
    }
    
    // Drop the table if it exists to start fresh
    echo "Dropping existing table...<br>";
    $db->exec("DROP TABLE IF EXISTS blog_posts");
    echo "✅ Table dropped<br>";
    
    // Create the table with the correct structure
    echo "Creating new table...<br>";
    $create_table = "CREATE TABLE blog_posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        excerpt TEXT,
        content LONGTEXT,
        image_url VARCHAR(500),
        author VARCHAR(100) DEFAULT 'Admin',
        status ENUM('published', 'draft') DEFAULT 'draft',
        read_time VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $db->exec($create_table);
    echo "✅ Blog posts table created successfully!<br>";
    
    // Verify the table structure
    echo "Verifying table structure...<br>";
    $table_info = $db->query("DESCRIBE blog_posts")->fetchAll(PDO::FETCH_ASSOC);
    echo "Final table structure:<br>";
    echo "<pre>";
    foreach ($table_info as $column) {
        echo "{$column['Field']} - {$column['Type']}<br>";
    }
    echo "</pre>";
    
    // Insert sample data
    echo "Inserting sample data...<br>";
    $sample_posts = [
        [
            'title' => 'Understanding and Managing Dementia: A Comprehensive Guide for Families',
            'excerpt' => 'Dementia affects millions of seniors worldwide. Learn about early signs, effective communication strategies, and creating a supportive environment.',
            'content' => 'Dementia is a challenging condition that affects millions of seniors and their families worldwide.',
            'author' => 'Dr. Sarah Johnson',
            'status' => 'published',
            'read_time' => '8 min read'
        ],
        [
            'title' => 'Senior Fitness: Safe Exercises for Maintaining Mobility and Strength',
            'excerpt' => 'Discover age-appropriate exercises that help seniors maintain independence, improve balance, and enhance overall quality of life.',
            'content' => 'Regular exercise is essential for seniors to maintain mobility, strength, and overall health.',
            'author' => 'Mike Thompson',
            'status' => 'published',
            'read_time' => '6 min read'
        ]
    ];
    
    $insert_count = 0;
    foreach ($sample_posts as $post) {
        $query = "INSERT INTO blog_posts (title, excerpt, content, author, status, read_time) 
                  VALUES (:title, :excerpt, :content, :author, :status, :read_time)";
        $stmt = $db->prepare($query);
        
        try {
            $stmt->execute($post);
            $insert_count++;
            echo "✅ Added: {$post['title']}<br>";
        } catch (PDOException $e) {
            echo "❌ Failed to add: {$post['title']} - Error: " . $e->getMessage() . "<br>";
        }
    }
    
    echo "<br>✅ Successfully inserted {$insert_count} sample posts!<br>";
    
    // Final verification
    $post_count = $db->query("SELECT COUNT(*) as count FROM blog_posts")->fetch(PDO::FETCH_ASSOC);
    echo "<br>Total posts in database: {$post_count['count']}<br>";
    
    echo "<br><div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
    echo "<strong>Setup Complete!</strong><br>";
    echo "<a href='blog.php' style='color: #155724;'>View Blog</a> | ";
    echo "<a href='admin/manage-blog.php' style='color: #155724;'>Manage Blog</a>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "</div>";
}
?>