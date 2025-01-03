<h2>Your Cart</h2>

@if (count($cart) > 0)
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                < </tr>
        </thead>
        <tbody>
            @foreach ($cart as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['price'] }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>Your cart is empty.</p>
@endif
