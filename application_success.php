<?php
// Database connection
$db = new mysqli('localhost', 'root', '8766722758', 'giga_data');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get application by email
$email = isset($_GET['email']) ? $_GET['email'] : '';
$stmt = $db->prepare("SELECT * FROM job_applications WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$application = $result->fetch_assoc();
$stmt->close();

if (!$application) {
    die("Application not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted - Giga Data</title>
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
        .confirmation-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .application-details {
            margin-top: 30px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: 600;
            width: 200px;
        }
        .photo-container {
            text-align: center;
            margin: 20px 0;
        }
        .photo-container img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 4px;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="logo">GIGA DATA</div>
    <h1>Application Submitted Successfully</h1>
    
    <div class="confirmation-container">
        <div class="success-message">
            Thank you for applying to Giga Data! We've received your application for the Data Scientist position.
        </div>
        
        <div class="application-details">
            <h2>Your Application Details</h2>
            
            <?php if (!empty($application['photo_path'])): ?>
                <div class="photo-container">
                    <img src="<?php echo htmlspecialchars($application['photo_path']); ?>" alt="Applicant Photo">
                </div>
            <?php endif; ?>
            
            <div class="detail-row">
                <div class="detail-label">Name:</div>
                <div><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Email:</div>
                <div><?php echo htmlspecialchars($application['email']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Phone:</div>
                <div><?php echo htmlspecialchars($application['phone'] ?: 'Not provided'); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Date of Birth:</div>
                <div><?php echo date('F j, Y', strtotime($application['dob'])); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Highest Qualification:</div>
                <div><?php echo htmlspecialchars($application['qualification']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">University/Institution:</div>
                <div><?php echo htmlspecialchars($application['university'] ?: 'Not provided'); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Years of Experience:</div>
                <div><?php echo htmlspecialchars($application['experience']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Key Skills:</div>
                <div><?php echo htmlspecialchars($application['skills']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Application Date:</div>
                <div><?php echo date('F j, Y \a\t g:i a', strtotime($application['application_date'])); ?></div>
            </div>
            
            <div style="margin-top: 30px;">
                <h3>Cover Letter</h3>
                <p><?php echo nl2br(htmlspecialchars($application['cover_letter'])); ?></p>
            </div>
        </div>
    </div>
</body>
</html>