<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .price-container {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }
        .price-usd {
            font-size: 2rem;
            font-weight: bold;
            color: #e74c3c;
        }
        .price-eur {
            font-size: 1.5rem;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="product-detail">
            <div>
                @if ($product->image && $product->image !== 'product-placeholder.jpg')
                    <img src="{{ asset('storage/uploads/' . $product->image) }}" class="product-detail-image">
                @else 
                    <img src="{{ asset('product-placeholder.jpg') }}" class="product-detail-image">
                @endif
            </div>
            <div class="product-detail-info">
                <h1 class="product-detail-title">{{ $product->name }}</h1>
                <p class="product-id">Product ID: {{ $product->id }}</p>

                <div class="price-container">
                    <span class="price-usd">${{ number_format($product->price, 2) }}</span>
                    <span class="price-eur">€{{ number_format($product->price * $exchangeRate, 2) }}</span>
                </div>

                <div class="divider"></div>

                <div class="product-detail-description">
                    <h4 class="description-title">Description</h4>
                    <p>{{ $product->description }}</p>
                </div>

                <div class="action-buttons">
                    <a href="{{ url('/') }}" class="btn btn-secondary">Back to Products</a>
                    <button class="btn btn-primary">Add to Cart</button>
                </div>

                <p style="margin-top: 20px; font-size: 0.9rem; color: #7f8c8d;">
                    Exchange Rate: 1 USD = {{ number_format($exchangeRate, 4) }} EUR
                </p>
            </div>
        </div>
    </div>
</body>
</html>
