<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{config('app.name')}}</title>

    <link rel="icon" href="/logo.webp"/>

    @vite('resources/frontend/main.ts')
    @inertiaHead
</head>
<body>
@inertia
</body>
</html>
