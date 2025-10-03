<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Hash Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Password Hash Generator</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
                            $password = $_POST['password'];
                            $hash = password_hash($password, PASSWORD_DEFAULT);
                            ?>
                            <div class="alert alert-success">
                                <h5>Password Hash Generated:</h5>
                                <code><?= $hash ?></code>
                            </div>
                            <div class="alert alert-info">
                                <h6>SQL Insert Example:</h6>
                                <pre>INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`) 
VALUES ('User Name', 'user@example.com', '<?= $hash ?>', 'student', NOW());</pre>
                            </div>
                            <?php
                        }
                        ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Enter Password:</label>
                                <input type="text" name="password" class="form-control" required>
                                <small class="text-muted">Enter the plain text password you want to hash</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Generate Hash</button>
                        </form>
                        
                        <hr>
                        
                        <h5>Pre-generated Test Passwords:</h5>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Password</th>
                                    <th>Hash</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>admin123</code></td>
                                    <td><small><?= password_hash('admin123', PASSWORD_DEFAULT) ?></small></td>
                                </tr>
                                <tr>
                                    <td><code>teacher123</code></td>
                                    <td><small><?= password_hash('teacher123', PASSWORD_DEFAULT) ?></small></td>
                                </tr>
                                <tr>
                                    <td><code>student123</code></td>
                                    <td><small><?= password_hash('student123', PASSWORD_DEFAULT) ?></small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
