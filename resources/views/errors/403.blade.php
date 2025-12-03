<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <style>
        body { background: #f8fafc; color: #333; font-family: 'Roboto', sans-serif; margin: 0; }
        .container { text-align: center; padding: 80px 20px; }
        .error-code { font-size: 96px; font-weight: 700; color: #e74c3c; }
        .error-message { font-size: 28px; margin: 20px 0; }
        .error-desc { color: #888; margin-bottom: 30px; }
        a { color: #3498db; text-decoration: none; font-weight: 700; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">{{ $code ?? 'Error' }}</div>
        <div class="error-message">{{ $message ?? 'Something went wrong.' }}</div>
        <div class="error-desc">{{ $desc ?? '' }}</div>
        <a href="{{ url('/') }}">Back to Home</a>
    </div>
</body>
</html>
