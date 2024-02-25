<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?=(isset($e) && $e->getMessage() != "" ? $e->getMessage() : "Page Not Found")?></title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .error-container {
      max-width: 400px;
      margin: auto;
      margin-top: 100px;
      text-align: center;
    }
    .error-message {
      font-size: 2.5rem;
      font-weight: bold;
    }
    .dotori-icon {
	  width: 100px;
      max-width: 150px;
      margin-bottom: 30px;
    }
  </style>
</head>
<body>
  <div class="error-container">
    <img class="dotori-icon" src="http://www.dotorioffice.com/dotorioffice2/library/dotori.svg" alt="Dotori Icon">
    <div class="error-message"><?=(isset($e) && $e->getMessage() != "" ? $e->getMessage() : "Page Not Found")?></div>
    <p>The requested URL was not found on this server. Please check the URL or contact the administrator.</p>
    <a href="./" class="btn btn-primary mt-3">Back to Home</a>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>
