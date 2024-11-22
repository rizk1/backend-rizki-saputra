document.addEventListener('DOMContentLoaded', function () {
    const app = document.getElementById('app');
    const content = document.getElementById('content');
    const navbar = document.getElementById('navbar');

    function renderNavbar() {
        navbar.innerHTML = `
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#" onclick="navigate('products')">E-commerce</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="nav-link" href="#" onclick="navigate('products')">Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="#" onclick="navigate('orders')">Orders</a></li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        ${localStorage.getItem('token') ? 
                            `<li class="nav-item"><a class="nav-link" href="#" onclick="logout()">Logout</a></li>` :
                            `<li class="nav-item"><a class="nav-link" href="#" onclick="navigate('login')">Login</a></li>`}
                    </ul>
                </div>
            </nav>
        `;
    }

    window.navigate = function(page) {
        switch (page) {
            case 'login':
                renderLogin();
                break;
            case 'products':
                renderProducts();
                break;
            case 'orders':
                renderOrders();
                break;
            default:
                renderLogin();
        }
    }

    function renderLogin() {
        content.innerHTML = `
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Login</div>
                        <div class="card-body">
                            <form id="loginForm">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await axios.post('/api/login', { email, password });
                localStorage.setItem('token', response.data.access_token);
                renderNavbar();
                navigate('products');
            } catch (error) {
                console.error('Login failed:', error);
            }
        });
    }

    window.renderProducts = function() {
        content.innerHTML = '<h2>Loading products...</h2>';
        axios.get('/api/products', {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        }).then(response => {
            const products = response.data.data;
            content.innerHTML = `
                <div class="d-flex justify-content-between mb-3">
                    <h2>Products</h2>
                    <button class="btn btn-success" onclick="createProduct()">Add Product</button>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${products.map(product => `
                            <tr>
                                <td>${product.name}</td>
                                <td>${product.price}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editProduct(${product.id})">Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">Delete</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }).catch(error => {
            console.error('Failed to fetch products:', error);
        });
    }

    window.createProduct = function() {
        content.innerHTML = `
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Add Product</div>
                        <div class="card-body">
                            <form id="productForm">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" id="price" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('productForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const name = document.getElementById('name').value;
            const price = document.getElementById('price').value;

            try {
                await axios.post('/api/products', { name, price }, {
                    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
                });
                navigate('products');
            } catch (error) {
                console.error('Failed to create product:', error);
            }
        });
    }

    window.editProduct = function(id) {
        axios.get(`/api/products/${id}`, {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        }).then(response => {
            const product = response.data.data;
            content.innerHTML = `
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Edit Product</div>
                            <div class="card-body">
                                <form id="productForm">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" class="form-control" value="${product.name}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" id="price" class="form-control" value="${product.price}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('productForm').addEventListener('submit', async function (e) {
                e.preventDefault();
                const name = document.getElementById('name').value;
                const price = document.getElementById('price').value;

                try {
                    await axios.put(`/api/products/${id}`, { name, price }, {
                        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
                    });
                    navigate('products');
                } catch (error) {
                    console.error('Failed to update product:', error);
                }
            });
        }).catch(error => {
            console.error('Failed to fetch product:', error);
        });
    }

    window.deleteProduct = function(id) {
        if (confirm('Are you sure you want to delete this product?')) {
            axios.delete(`/api/products/${id}`, {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
            }).then(() => {
                navigate('products');
            }).catch(error => {
                console.error('Failed to delete product:', error);
            });
        }
    }

    window.renderOrders = function() {
        content.innerHTML = '<h2>Loading orders...</h2>';
        axios.get('/api/orders', {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        }).then(response => {
            const orders = response.data.data;
            content.innerHTML = `
                <h2>Your Orders</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${orders.map(order => `
                            <tr>
                                <td>${order.product.name}</td>
                                <td>${order.quantity}</td>
                                <td>${order.total_price}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" onclick="viewOrder(${order.id})">Details</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }).catch(error => {
            console.error('Failed to fetch orders:', error);
        });
    }

    window.logout = function() {
        localStorage.removeItem('token');
        renderNavbar();
        navigate('login');
    }

    renderNavbar();
    navigate('login');
}); 