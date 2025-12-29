<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transport</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Additional wallboard-specific overrides */
        body {
            font-family: 'JetBrains Mono', monospace !important;
            margin: 0 !important;
            padding: 40px !important;
            background-color: #000000 !important;
            color: #ff6600 !important;
            font-weight: 800 !important;
            font-size: 28px !important;
            text-transform: uppercase !important;
            letter-spacing: 2px !important;
            overflow-x: auto !important;
        }

        #app {
            background: transparent !important;
        }

        /* Ensure all scraped content follows wallboard theme */
        * {
            color: #ff6600 !important;
            background: transparent !important;
        }
    </style>
</head>
<body>
    <div id="app">
    </div>

</body>
</html>
