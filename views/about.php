<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        h1 {
            color: #333;
        }

        a {
            color: #007cba;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1><?= htmlspecialchars($title) ?></h1>
    <p>This is the about page rendered through a controller!</p>
    <p>Our company has been providing excellent services since 2025.</p>
    <a href="/home">← Back to Controller Home</a>
</body>

</html>