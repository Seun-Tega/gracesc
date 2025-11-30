<?php
// setup-blog-table.php
include 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Drop table if exists (for testing)
    // $db->exec("DROP TABLE IF EXISTS blog_posts");
    
    // Create blog_posts table with correct structure
    $create_table = "CREATE TABLE IF NOT EXISTS blog_posts (
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
    )";
    
    $db->exec($create_table);
    echo "✅ Blog posts table created successfully!<br>";
    
    // Check if table is empty and insert sample data
    $check_empty = $db->query("SELECT COUNT(*) as count FROM blog_posts")->fetch(PDO::FETCH_ASSOC);
    
    if ($check_empty['count'] == 0) {
        $sample_posts = [
            [
                'title' => 'Understanding and Managing Dementia: A Comprehensive Guide for Families',
                'excerpt' => 'Dementia affects millions of seniors worldwide. Learn about early signs, effective communication strategies, and creating a supportive environment.',
                'content' => 'Dementia is a challenging condition that affects millions of seniors and their families worldwide. Understanding the early signs is crucial for providing the best care possible.

## Early Signs of Dementia
- Memory loss that disrupts daily life
- Difficulty planning or solving problems
- Trouble completing familiar tasks
- Confusion with time or place
- Changes in mood and personality

## Effective Communication Strategies
1. Speak clearly and slowly
2. Use simple words and short sentences
3. Maintain eye contact
4. Be patient and give time for responses
5. Use visual cues when helpful

Creating a supportive environment involves making the home safe, establishing routines, and ensuring proper medical care.',
                'author' => 'Dr. Sarah Johnson',
                'status' => 'published',
                'read_time' => '8 min read'
            ],
            [
                'title' => 'Senior Fitness: Safe Exercises for Maintaining Mobility and Strength',
                'excerpt' => 'Discover age-appropriate exercises that help seniors maintain independence, improve balance, and enhance overall quality of life.',
                'content' => 'Regular exercise is essential for seniors to maintain mobility, strength, and overall health. Here are safe and effective exercises for older adults.

## Balance Exercises
- Heel-to-toe walk
- Standing on one foot
- Tai chi movements
- Leg raises while holding onto a chair

## Strength Training
- Chair squats
- Wall push-ups
- Arm raises with light weights
- Leg extensions
- Calf raises

## Flexibility Exercises
- Neck rotations
- Shoulder rolls
- Seated hamstring stretches
- Ankle circles

Always consult with a healthcare provider before starting any new exercise program.',
                'author' => 'Mike Thompson',
                'status' => 'published',
                'read_time' => '6 min read'
            ],
            [
                'title' => 'Nutrition for Seniors: Building a Balanced Diet for Optimal Health',
                'excerpt' => 'Learn about essential nutrients, meal planning strategies, and dietary considerations for seniors with specific health conditions.',
                'content' => 'Proper nutrition plays a vital role in maintaining health and vitality in senior years. As we age, our nutritional needs change.

## Essential Nutrients for Seniors
- **Calcium and Vitamin D**: For bone health
- **Fiber**: For digestive health
- **Protein**: For muscle maintenance
- **Vitamin B12**: For nerve function
- **Potassium**: For blood pressure control

## Meal Planning Tips
- Eat smaller, more frequent meals
- Include a variety of colorful fruits and vegetables
- Choose whole grains over refined grains
- Stay hydrated with water and other fluids
- Limit sodium and processed foods

## Special Considerations
For seniors with diabetes, heart conditions, or other health issues, dietary adjustments may be necessary. Always work with a healthcare professional.',
                'author' => 'Nutritionist Emily Chen',
                'status' => 'published',
                'read_time' => '7 min read'
            ]
        ];
        
        foreach ($sample_posts as $post) {
            $query = "INSERT INTO blog_posts (title, excerpt, content, author, status, read_time) 
                      VALUES (:title, :excerpt, :content, :author, :status, :read_time)";
            $stmt = $db->prepare($query);
            $stmt->execute($post);
        }
        echo "✅ Sample blog posts added successfully!<br>";
    } else {
        echo "✅ Blog posts table already exists with {$check_empty['count']} posts.<br>";
    }
    
    echo "<br><a href='blog.php'>View Blog</a> | <a href='admin/manage-blog.php'>Manage Blog</a>";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>