<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Product Focus')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom styles -->
    @yield('styles')
</head>
<body>
<header class="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Product Focus</h1>
            </div>
            <div class="col-md-6 text-md-end">
                <img src="{{ asset('images/product-focus-logo.png') }}" alt="Product Focus Logo" class="logo">
            </div>
        </div>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">© Product Focus {{ date('Y') }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">www.productfocus.com</p>
            </div>
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js for any charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom scripts -->
@yield('scripts')
</body>
</html>
