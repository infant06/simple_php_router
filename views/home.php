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
    <p>This is the home page rendered through a controller!</p>
    <ul>
        <li><a href="/home/about">About (Controller)</a></li>
        <li><a href="/home/contact">Contact (Controller)</a></li>
        <li><a href="/">Simple Routes</a></li>
    </ul>
</body>

</html>