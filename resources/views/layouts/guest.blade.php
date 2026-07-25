<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'FreshMart') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Khmer Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Bayon&family=Bokor&family=Carter+One&family=Chenla&family=Cutive+Mono&family=Kdam+Thmor+Pro&family=Khmer&family=Koh+Santepheap:wght@100;300;400;700;900&family=Koulen&family=Luckiest+Guy&family=Merienda:wght@300..900&family=Metal&family=Rowdies:wght@300;400;700&family=Rubik+Vinyl&family=Young+Serif&display=swap" rel="stylesheet">
    
    <style>
        body { background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); min-height: 100vh; display: flex; align-items: center; }
        .auth-card { border: none; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .brand { font-size: 1.8rem; font-weight: 700; color: #2e7d32; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card auth-card p-4">
                    <div class="text-center mb-4">
                        <a href="{{ route('home') }}" class="brand"><i class="bi bi-basket-fill text-success"></i> FreshMart</a>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
