<?php
require_once __DIR__ . '/../../loader.php';
use App\Libs\UserValidator;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'] ?? null;
  $user = UserValidator::getUserbyEmail($email);
  if (is_null($user)) {
    echo "<div id='result'>User Not Found</div>";
  } else {
    $jwt = UserValidator::createApiKey($user);
    echo "<div id='result'>$jwt</div>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Request API Key</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .form-container {
      background: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
    }

    .form-container h2 {
      margin-bottom: 20px;
      font-size: 24px;
      text-align: center;
      color: #333;
    }

    label {
      font-size: 14px;
      color: #555;
    }

    input[type="email"] {
      width: 100%;
      padding: 10px;
      margin: 8px 0 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
      width: 100%;
    }

    input[type="submit"]:hover {
      background-color: #45a049;
    }
    #result {
  font-size: 16px;
  line-height: 1.5;
  text-align: left;
  background-color: #eafbea;
  color: #2e7d32;
  border: 1px solid #c8e6c9;
  border-radius: 8px;
  padding: 20px;
  margin: 20px auto;
  max-width: 90%;
  word-break: break-all;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}


  </style>
</head>

<body>

  <div class="form-container">
    <h2>Get Your API Token</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
      <label for="email">Enter your email:</label>
      <input type="email" id="email" name="email" placeholder="you@example.com" required>

      <input type="submit" value="Request API Token">
    </form>
  </div>
</body>

</html>