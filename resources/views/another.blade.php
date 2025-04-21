<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if (isset($title_product) and isset($price_product))
        <h2>Product Title: {{ $title_product }}</h2>
        <h2>{{$price_product}}</h2>
    @else
        <h2>Title or price not found!</h2>
    @endif
</body>
</html>