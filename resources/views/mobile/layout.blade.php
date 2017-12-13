<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title or '一起生活'}}</title>
    <link rel="stylesheet" href="{{ mix('/frontend/mobile.css') }}?_v=9">
    <script src="{{ mix('/frontend/mobile.js') }}"></script>
    <script src="/layer_mobile/layer.js"></script>
</head>
<body class="p-wrap">
@section('content')
@show
</body>
</html>