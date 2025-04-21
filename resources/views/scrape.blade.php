<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Scrape data</title>
    <style>
        body{
            background-color: rgb(13, 28, 56);
            color: white;
        }
    </style>
</head>
<body>
    <form action="{{ route('scrape_data') }}" method="GET">
        <label for="url">Write the URL:</label>
        <input type="text" id="url" name="site" placeholder="https://example.com" required>
        <button type="submit">Scrape</button>
    </form>
    
    
    
    
</body>
</html>