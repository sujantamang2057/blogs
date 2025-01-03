@foreach ($cart as $product)
    <div class="card">
        <h3>Title:{{ $product->title }}</h3>
        <p>Price: ${{ $product->status }}</p>
        <button class="add-to-cart" data-id="{{ $product->id }}" data-name="{{ $product->title }}"
            data-price="{{ $product->status }}">
            Add to Cart
        </button>
    </div>
@endforeach
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.add-to-cart').click(function() {
            // Get product details from the button's data attributes
            var id = $(this).data('id');
            var name = $(this).data('name');
            var price = $(this).data('price');

            // Send the product data to the server via AJAX
            $.ajax({
                url: "{{ route('cart.add') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    name: name,
                    price: price,
                },
                success: function(response) {
                    alert(response.message);
                },
                error: function() {
                    alert('Something went wrong');
                }
            });
        });
    });
</script>
