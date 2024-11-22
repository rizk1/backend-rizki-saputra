<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple E-commerce</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Simple E-commerce</h1>

        <div id="login" class="mt-4">
            <h2>Login</h2>
            <div class="form-group">
                <input type="email" id="email" class="form-control" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" id="password" class="form-control" placeholder="Password">
            </div>
            <button class="btn btn-primary" onclick="login()">Login</button>
        </div>

        <button id="logout-button" class="btn btn-danger" style="display:none;" onclick="logout()">Logout</button>

        <div id="product-list-section" class="mt-4" style="display:none;">
            <h2>Product List</h2>
            <button class="btn btn-info" onclick="listProducts()">List Products</button>
            <div id="product-list" class="mt-3"></div>
        </div>

        <div id="product-crud" class="mt-4" style="display:none;">
            <h2>Product Management</h2>
            <div class="form-group">
                <input type="text" id="product-name" class="form-control" placeholder="Product Name">
            </div>
            <div class="form-group">
                <input type="number" id="product-price" class="form-control" placeholder="Product Price">
            </div>
            <button class="btn btn-success" onclick="createProduct()">Create Product</button>
        </div>

        <div id="order-list" class="mt-4" style="display:none;">
            <h2>Order List</h2>
            <button class="btn btn-info" onclick="listOrders()">List Orders</button>
            <div id="orders" class="mt-3"></div>
        </div>

        <div id="checkout" class="mt-4" style="display:none;">
            <h2>Checkout</h2>
            <div class="form-group">
                <input type="number" id="checkout-product-id" class="form-control" placeholder="Product ID">
            </div>
            <div class="form-group">
                <input type="number" id="checkout-quantity" class="form-control" placeholder="Quantity">
            </div>
            <button class="btn btn-primary" onclick="checkout()">Checkout</button>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('token');
        const role = localStorage.getItem('role');

        if (token) {
            document.getElementById('login').style.display = 'none';
            document.getElementById('product-list-section').style.display = 'block';
            document.getElementById('logout-button').style.display = 'block';
            if (role === 'merchant') {
                document.getElementById('product-crud').style.display = 'block';
            }
            if (role === 'customer') {
                document.getElementById('checkout').style.display = 'block';
            }
            document.getElementById('order-list').style.display = 'block';
            listProducts();
            listOrders();
        }

        function login() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            axios.post('/api/login', { email, password })
                .then(response => {
                    const token = response.data.access_token;
                    const userRole = response.data.role;
                    localStorage.setItem('token', token);
                    localStorage.setItem('role', userRole);
                    window.location.reload();
                })
                .catch(error => console.error('Login failed:', error));
        }

        function createProduct() {
            const name = document.getElementById('product-name').value;
            const price = document.getElementById('product-price').value;
            console.log(token);
            
            axios.post('/api/products', { name, price }, {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(response => {
                document.getElementById('product-name').value = '';
                document.getElementById('product-price').value = '';
                console.log('Product created:', response.data);
                listProducts();
            })
            .catch(error => console.error('Error creating product:', error));
        }

        function listProducts() {
            axios.get('/api/products', {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(response => {
                const products = response.data.data;
                const productList = document.getElementById('product-list');
                productList.innerHTML = `
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                ${role === 'merchant' ? '<th>Actions</th>' : ''}
                            </tr>
                        </thead>
                        <tbody>
                            ${products.map(product => `
                                <tr>
                                    <td>${product.id}</td>
                                    <td>${product.name}</td>
                                    <td>Rp ${product.price}</td>
                                    ${role === 'merchant' && !product.orders.length ? `<td><button class="btn btn-danger" onclick="deleteProduct(${product.id})">Delete</button></td>` : ''}
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            })
            .catch(error => console.error('Error listing products:', error));
        }

        function deleteProduct(productId) {
            axios.delete(`/api/products/${productId}`, {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(response => {
                console.log('Product deleted:', response.data);
                listProducts();
            })
            .catch(error => console.error('Error deleting product:', error));
        }

        function listOrders() {
            axios.get('/api/orders', {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(response => {
                const orders = response.data.data;
                const ordersDiv = document.getElementById('orders');
                ordersDiv.innerHTML = `
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Buy Price</th>
                                <th>Total Price</th>
                                <th>Discount</th>
                                <th>Final Price</th>
                                <th>Free Shipping</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${orders.map(order => `
                                <tr>
                                    <td>${order.id}</td>
                                    <td>${order.product?.name}</td>
                                    <td>${order.quantity}</td>
                                    <td>Rp ${order.buy_price}</td>
                                    <td>Rp ${order.total_price}</td>
                                    <td>Rp ${order.discount}</td>
                                    <td>Rp ${order.final_price}</td>
                                    <td>${order.free_shipping ? 'Yes' : 'No'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            })
            .catch(error => console.error('Error listing orders:', error));
        }

        function checkout() {
            const productId = document.getElementById('checkout-product-id').value;
            const quantity = document.getElementById('checkout-quantity').value;

            axios.post('/api/checkout', { product_id: productId, quantity }, {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(response => {
                document.getElementById('checkout-product-id').value = '';
                document.getElementById('checkout-quantity').value = '';
                console.log('Checkout successful:', response.data);
                listOrders();
            })
            .catch(error => console.error('Error during checkout:', error));
        }

        function logout() {
            axios.post('/api/logout', {}, {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(response => {
                localStorage.removeItem('token');
                localStorage.removeItem('role');
                window.location.reload();
            })
            .catch(error => {
                console.error('Error during logout:', error);
            });
        }
    </script>
</body>
</html>
