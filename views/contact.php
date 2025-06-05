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
    <p>Get in touch with us!</p>
    <p>Email: contact@example.com</p>
    <p>Phone: +1 (555) 123-4567</p>
    <a href="/home">← Back to Controller Home</a>
</body>

</html>