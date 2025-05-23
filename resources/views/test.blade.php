<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   @if(!empty($userData))
   @foreach ($userData as $key => $value)
       <p>key: {{ $key['DatiImmatricolazioni'] }} and value: </p>
    @foreach ($value as $key2 => $value2 )
        <p>key2: {{ $key2 }} and value2: {{ $value2 }}
    @endforeach    
    </p>
   @endforeach
   @else
   <p> Something went wrong! Try putting the right car code </P>
   @endif
</body>
</html>

