<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if (isset($product_info))
        @if ($product_info[0] != null)
            <h1>Title: {{$product_info[0]}}</h1>
            @if ($product_info[1] != null)
                <h1>Limited time offer</h1>
            @endif
            @if ($product_info[2] != null)
                <h1>Discount: {{$product_info[2]}}</h1>
                <h1>Original price: {{$product_info[3]}}</h1>
                <h1>Price after discount: {{$product_info[4]}}</h1>
            @else
                <h1>Price: {{$product_info[3]}}</h1>
            @endif
        @else
            <h1>Make sure to put the entire link of the product!</h1>
        @endif
    @else
        <h2>Title or price not found!</h2>
    @endif
</body>
</html>