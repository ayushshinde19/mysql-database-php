<?php
// Database connection
$db = new mysqli('localhost', 'root', 'xxx', 'giga_data');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle file upload
    $photoPath = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $photoPath = $uploadDir . $photoName;
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
    }

    // Prepare and bind
    $stmt = $db->prepare("INSERT INTO job_applications 
        (first_name, last_name, email, phone, dob, qualification, university, experience, skills, photo_path, cover_letter) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssssisss", 
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['dob'],
        $_POST['qualification'],
        $_POST['university'],
        $_POST['experience'],
        $_POST['skills'],
        $photoPath,
        $_POST['cover_letter']
    );

    // Execute and redirect
    if ($stmt->execute()) {
        header("Location: application_success.php?email=" . urlencode($_POST['email']));
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giga Data - Data Scientist Application</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f7fa;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        textarea {
            min-height: 100px;
        }
        .submit-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: #2980b9;
        }
        .error {
            color: #e74c3c;
            margin-top: 5px;
        }
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
    </style>
</head>
<body>
    <div class="logo">GIGA DATA</div>
    <h1>Data Scientist Job Application</h1>
    
    <div class="form-container">
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="two-column">
                <div class="form-group">
                    <label for="first_name">First Name*</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name*</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
            </div>
            
            <div class="two-column">
                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone">
                </div>
            </div>
            
            <div class="form-group">
                <label for="dob">Date of Birth*</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            
            <div class="form-group">
                <label for="qualification">Highest Qualification*</label>
                <select id="qualification" name="qualification" required>
                    <option value="">Select</option>
                    <option value="PhD">PhD</option>
                    <option value="Master's Degree">Master's Degree</option>
                    <option value="Bachelor's Degree">Bachelor's Degree</option>
                    <option value="Diploma">Diploma</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="university">University/Institution</label>
                <input type="text" id="university" name="university">
            </div>
            
            <div class="form-group">
                <label for="experience">Years of Experience*</label>
                <input type="number" id="experience" name="experience" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="skills">Key Skills (comma separated)*</label>
                <textarea id="skills" name="skills" placeholder="Python, Machine Learning, SQL, etc." required></textarea>
            </div>
            
            <div class="form-group">
                <label for="photo">Upload Photo</label>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>
            
            <div class="form-group">
                <label for="cover_letter">Cover Letter*</label>
                <textarea id="cover_letter" name="cover_letter" required placeholder="Tell us why you're the perfect candidate for this position..."></textarea>
            </div>
            
            <button type="submit" class="submit-btn">Submit Application</button>
        </form>
    </div>
</body>
</html>
