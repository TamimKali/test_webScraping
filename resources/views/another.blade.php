<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>


    @if ($error || !$product)
        <h2>Title or price not found! {{$error}}</h2>
    @else
        @if ($product->title == null)
            <h1>Make sure to put the entire link of the product!</h1>
        @else
            <h1>Title: {{$product->title}}</h1>
        @if ($product->limited_time_offer == null)
                @continue
        @endif
        @if ($product->discount_percentage == null)
            @continue
        @endif
        @if ($product->original_price == null)
            <h2>Title or price not found! {{$error}}</h2>
            
        @endif
            
    @endif

{{-- 
            @if ($product_info[1] != null)
                <h1>{{$product_info[1]}}</h1>
            @endif

            @if ($product_info[2] != null)
                <h1>Discount: {{$product_info[2]}}</h1>
                <h1>Original price: {{$product_info[3]}}</h1>
                <h1>Current price: {{ $product_info[4] }}
                    @php echo floatval($product_info[3]) - floatval($product_info[4]); @endphp$ less</h1>
                <img src="{{$product_info[5]}}" alt="{{$product_info[1]}}">
                @if ($product_info[6] != null)
                    <h1>{{$product_info[6]}}</h1>
                @endif
            @else
                <h1>Price: {{$product_info[3]}}</h1>
                <img src="{{$product_info[4]}}" alt="{{$product_info[1]}}">
                @if ($product_info[5] != null)
                    <h1>{{$product_info[5]}}</h1>
                @endif
            @endif --}}
        @endif
    @endif
</body>
</html>
