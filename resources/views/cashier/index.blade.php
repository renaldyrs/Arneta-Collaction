@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Point of Sale System</h1>
            <p class="text-gray-600 dark:text-gray-400">Efficient and modern sales transaction management</p>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Products Column (Left) -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                    <!-- Products Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                            </svg>
                            Product Catalog
                        </h2>
                    </div>

                    <div class="p-6">
                        <!-- Search and Filter Section -->
                        <div class="mb-6">
                            <div class="relative mb-4">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="productSearch" placeholder="Search products..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            <!-- Categories -->
                            <div class="flex flex-wrap gap-2">
                                <button class="category-btn px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200 active:bg-blue-300 transition-colors dark:bg-blue-900 dark:text-blue-200"
                                    data-category="all">All</button>
                                @foreach($categories as $category)
                                    <button class="category-btn px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm hover:bg-gray-200 active:bg-gray-300 transition-colors dark:bg-gray-700 dark:text-gray-200"
                                        data-category="{{ $category->id }}">{{ $category->name }}</button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto max-h-[32rem] pr-2" id="productGrid">
                            @foreach($products as $product)
                                <div class="product-card bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow cursor-pointer flex flex-col"
                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                    data-price="{{ $product->price }}" data-stock="{{ $product->stock }}"
                                    data-category="{{ $product->category_id }}" data-barcode="{{ $product->barcode }}"
                                    data-sizes="{{ $product->sizes->map(fn($size) => ['name' => $size->name, 'stock' => $size->pivot->stock])->toJson() }}">
                                    <div class="h-32 bg-gray-100 dark:bg-gray-600 rounded-md mb-2 overflow-hidden flex items-center justify-center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="text-gray-400 dark:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex justify-between items-start mt-1">
                                            <span class="font-medium text-gray-800 dark:text-white truncate">{{ $product->name }}</span>
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded dark:bg-blue-900 dark:text-blue-200">{{ $product->barcode }}</span>
                                        </div>
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="text-blue-600 font-bold dark:text-blue-400">Rp {{ number_format($product->price) }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Stock: {{ $product->stock }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shopping Cart Column (Right) -->
            <div class="col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden h-full">
                    <!-- Cart Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                            Shopping Cart
                        </h2>
                        <button id="startScannerBtn" class="bg-blue-400 text-white p-2 rounded-lg hover:bg-blue-600 transition-colors" title="Scan Barcode">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                    </div>

                    <!-- Scanner Modal -->
                    <div id="scannerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 w-full max-w-md">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-bold dark:text-white">Barcode Scanner</h2>
                                <button id="stopScannerBtn" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div id="scanner" class="w-full h-64 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden"></div>
                            <div class="mt-4 text-center text-gray-600 dark:text-gray-300">
                                <p>Scan product barcode to add to cart</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Cashier Info -->
                        <div class="bg-blue-50 dark:bg-gray-700 rounded-lg p-4 mb-6 border border-blue-100 dark:border-gray-600">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Cashier:</span>
                                <span class="font-medium text-gray-800 dark:text-white">{{ Auth::user()->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Date:</span>
                                <span class="text-sm text-gray-800 dark:text-gray-200">{{ now()->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div class="mb-6 max-h-64 overflow-y-auto border-b pb-4" id="cartItems">
                            <div class="text-center py-8" id="emptyCartMessage">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="mt-2 text-gray-500 dark:text-gray-400">Your cart is empty</p>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Subtotal:</span>
                                <span class="font-medium" id="subtotal">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Discount:</span>
                                <span class="font-medium text-red-600" id="discount">Rp 0</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="font-bold text-gray-800 dark:text-white">Total:</span>
                                <span class="font-bold text-lg text-blue-600 dark:text-blue-400" id="total">Rp 0</span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method</label>
                            <select id="paymentMethod" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Payment</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Payment Amount -->
                        <div class="mb-4">
                            <label for="paymentAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Amount</label>
                            <input type="number" id="paymentAmount" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Enter payment amount">
                        </div>

                        <!-- Change Amount -->
                        <div class="mb-6">
                            <label for="changeAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Change</label>
                            <input type="text" id="changeAmount" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-gray-100 dark:bg-gray-600 dark:text-white" readonly>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-3">
                            <button id="cancelBtn" class="bg-gray-200 text-gray-800 py-3 rounded-lg hover:bg-gray-300 transition-colors dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Cancel
                            </button>
                            <button id="checkoutBtn" class="bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let cart = [];
            const cartItemsEl = document.getElementById('cartItems');
            const emptyCartMessage = document.getElementById('emptyCartMessage');
            const subtotalEl = document.getElementById('subtotal');
            const totalEl = document.getElementById('total');
            const discountEl = document.getElementById('discount');
            const paymentMethodEl = document.getElementById('paymentMethod');
            const checkoutBtn = document.getElementById('checkoutBtn');
            const productSearch = document.getElementById('productSearch');
            const productGrid = document.getElementById('productGrid');
            const paymentAmountEl = document.getElementById('paymentAmount');
            const changeAmountEl = document.getElementById('changeAmount');

            const startScannerBtn = document.getElementById('startScannerBtn');
            const stopScannerBtn = document.getElementById('stopScannerBtn');
            const scannerModal = document.getElementById('scannerModal');
            const scannerElement = document.getElementById('scanner');

            // Fungsi untuk update tampilan keranjang
            // Fungsi untuk update tampilan keranjang
            function updateCart() {
                cartItemsEl.innerHTML = '';

                if (cart.length === 0) {
                    cartItemsEl.appendChild(emptyCartMessage);
                    subtotalEl.textContent = 'Rp 0';
                    totalEl.textContent = 'Rp 0';
                    discountEl.textContent = 'Rp 0';
                    checkoutBtn.disabled = true;
                    return;
                }

                let subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                let discount = 0;
                let total = subtotal - discount;

                subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                discountEl.textContent = 'Rp ' + discount.toLocaleString('id-ID');
                totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');

                cart.forEach((item, index) => {
                    const itemEl = document.createElement('div');
                    itemEl.className = 'flex justify-between items-center py-2 border-b';
                    itemEl.innerHTML = `
                <div class="flex-1">
                    <div class="font-medium">${item.name} ${item.size ? `(${item.size})` : ''}</div>
                    <div class="flex items-center mt-1">
                        <button class="quantity-btn px-2 py-1 bg-gray-200 rounded" data-index="${index}" data-action="decrease">
                            <i class="fas fa-minus text-xs"></i>
                        </button>
                        <input type="number" min="1" max="${item.stock}" 
                            value="${item.quantity}" 
                            class="quantity-input w-12 mx-2 border rounded text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            data-index="${index}">
                        <button class="quantity-btn px-2 py-1 bg-gray-200 rounded" data-index="${index}" data-action="increase">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                        <span class="ml-4 text-blue-600">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                    </div>
                </div>
                <button class="remove-btn ml-2 text-red-500 hover:text-red-700" data-index="${index}">
                    <i class="fas fa-trash"></i>
                </button>
            `;
                    cartItemsEl.appendChild(itemEl);
                });

                // Tambahkan event listener untuk input manual
                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.addEventListener('change', function () {
                        const index = parseInt(this.dataset.index);
                        let newQuantity = parseInt(this.value);

                        // Validasi input
                        if (isNaN(newQuantity) || newQuantity < 1) {
                            newQuantity = 1;
                            this.value = 1;
                        } else if (newQuantity > cart[index].stock) {
                            newQuantity = cart[index].stock;
                            this.value = cart[index].stock;
                            showErrorAlert('Stok tidak mencukupi');
                        }

                        cart[index].quantity = newQuantity;
                        updateCart();
                    });

                    // Validasi saat mengetik
                    input.addEventListener('keydown', function (e) {
                        // Blokir karakter non-angka
                        if (['e', 'E', '+', '-', '.'].includes(e.key)) {
                            e.preventDefault();
                        }
                    });

                    // Pastikan nilai tetap valid saat kehilangan fokus
                    input.addEventListener('blur', function () {
                        if (this.value === '') {
                            this.value = 1;
                            const index = parseInt(this.dataset.index);
                            cart[index].quantity = 1;
                            updateCart();
                        }
                    });
                });

                checkoutBtn.disabled = cart.length === 0 || !paymentMethodEl.value;
            }



            // Event listener untuk produk
            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const price = parseFloat(this.dataset.price);
                    const stock = parseInt(this.dataset.stock); // Stok produk tanpa ukuran
                    const sizes = JSON.parse(this.dataset.sizes); // Data ukuran produk

                    // Jika produk memiliki ukuran
                    if (sizes.length > 0) {
                        let sizeOptions = sizes.map(size => `${size.name} (Stok: ${size.stock})`).join('\n');
                        let selectedSize = prompt(`Pilih ukuran untuk produk "${name}":\n${sizeOptions}`);

                        // Cari ukuran yang dipilih
                        const size = sizes.find(s => s.name.toLowerCase() === selectedSize?.toLowerCase());

                        if (!size) {
                            alert('Ukuran tidak valid atau stok tidak mencukupi.');
                            return;
                        }

                        // Periksa stok ukuran
                        if (size.stock <= 0) {
                            alert('Stok untuk ukuran ini tidak mencukupi.');
                            return;
                        }

                        // Tambahkan produk dengan ukuran ke keranjang
                        const existingItem = cart.find(item => item.id === id && item.size === size.name);

                        if (existingItem) {
                            if (existingItem.quantity < size.stock) {
                                existingItem.quantity += 1;
                            } else {
                                alert('Stok tidak mencukupi.');
                            }
                        } else {
                            cart.push({
                                id,
                                name,
                                price,
                                size: size.name,
                                quantity: 1,
                                stock: size.stock
                            });
                        }
                    } else {
                        // Jika produk tidak memiliki ukuran
                        const existingItem = cart.find(item => item.id === id);

                        if (existingItem) {
                            if (existingItem.quantity < stock) {
                                existingItem.quantity += 1;
                            } else {
                                alert('Stok tidak mencukupi.');
                            }
                        } else {
                            cart.push({
                                id,
                                name,
                                price,
                                quantity: 1,
                                stock
                            });
                        }
                    }

                    updateCart();
                });
            });

            // Event delegation untuk tombol quantity dan hapus
            cartItemsEl.addEventListener('click', function (e) {
                // Tangani tombol +/-
                if (e.target.closest('.quantity-btn')) {
                    const btn = e.target.closest('.quantity-btn');
                    const index = parseInt(btn.dataset.index);
                    const action = btn.dataset.action;

                    if (action === 'increase') {
                        if (cart[index].quantity < cart[index].stock) {
                            cart[index].quantity += 1;
                        } else {
                            showErrorAlert('Stok tidak mencukupi');
                        }
                    } else if (action === 'decrease') {
                        if (cart[index].quantity > 1) {
                            cart[index].quantity -= 1;
                        }
                    }

                    updateCart();
                }

                // Tangani tombol hapus
                if (e.target.closest('.remove-btn')) {
                    const btn = e.target.closest('.remove-btn');
                    const index = parseInt(btn.dataset.index);
                    cart.splice(index, 1);
                    updateCart();
                }
            });

            // Event listener untuk metode pembayaran
            paymentMethodEl.addEventListener('change', function () {
                const total = parseFloat(totalEl.textContent.replace(/[^\d]/g, '')) || 0;

                if (this.value === '1') {
                    // Jika metode pembayaran tunai, aktifkan input jumlah pembayaran
                    paymentAmountEl.disabled = false;
                    paymentAmountEl.value = '';
                    changeAmountEl.value = 'Rp 0';
                } else if (this.value === '2' || this.value === '3') {
                    // Jika metode pembayaran QRIS atau transfer, paymentAmount = total
                    paymentAmountEl.disabled = true;
                    paymentAmountEl.value = total;
                    changeAmountEl.value = 'Rp 0';
                }

                // Perbarui tombol checkout
                checkoutBtn.disabled = cart.length === 0 || !this.value;
            });

            // Event listener untuk tombol checkout
            checkoutBtn.addEventListener('click', async function () {
                try {
                    // Tampilkan loading
                    checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                    checkoutBtn.disabled = true;

                    const response = await fetch('{{ route("cashier.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            items: cart,
                            payment_method_id: paymentMethodEl.value,
                            payment_amount: paymentAmountEl.value,
                            change_amount: changeAmountEl.value.replace(/[^0-9]/g, ''),
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }

                    if (data.success) {
                        // Tampilkan notifikasi sukses pembayaran
                        showPaymentSuccessAlert(data.message, data.transaction);




                        // Reset keranjang
                        cart = [];
                        updateCart();
                        paymentMethodEl.value = '';
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showErrorAlert(error.message);
                } finally {
                    checkoutBtn.innerHTML = '<i class="fas fa-check mr-1"></i> Bayar';
                    checkoutBtn.disabled = cart.length === 0 || !paymentMethodEl.value;
                }
            });

            // Fungsi untuk menampilkan alert error
            function showErrorAlert(message) {
                const alert = document.createElement('div');
                alert.className = 'fixed top-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 z-50 rounded shadow-lg';
                alert.innerHTML = `
                                                                                <div class="flex items-center">
                                                                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                                                                    <span>${message}</span>
                                                                                </div>
                                                                            `;
                document.body.appendChild(alert);

                setTimeout(() => {
                    alert.remove();
                }, 5000);
            }

            // Fungsi untuk menampilkan alert sukses pembayaran
            function showPaymentSuccessAlert(message, transaction) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil',
                    text: message,
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    // Cetak struk setelah alert selesai
                    printReceipt(transaction);
                });
            }


            // Event listener untuk pencarian produk
            productSearch.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.product-card').forEach(card => {
                    const name = card.dataset.name.toLowerCase();
                    const barcode = card.dataset.barcode.toLowerCase();

                    if (name.includes(searchTerm) || barcode.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }

                });
            });

            // Event listener untuk filter kategori
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const category = this.dataset.category;
                    document.querySelectorAll('.product-card').forEach(card => {
                        if (category === 'all' || card.dataset.category === category) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Update active button
                    document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('bg-blue-600', 'text-white'));
                    this.classList.add('bg-blue-600', 'text-white');
                });
            });

            // Fungsi untuk menampilkan invoice
            function showInvoice(transaction) {
                // Implementasi tampilan invoice
                console.log('Invoice for transaction:', transaction);
                // Dalam implementasi nyata, ini akan membuka modal atau window baru
                // untuk menampilkan invoice dan mencetaknya
                window.open(`/cashier/invoice/${transaction.id}`, '_blank');
            }

            function printReceipt(transaction) {
                // Buka halaman cetak struk
                const printWindow = window.open(`/cashier/print/${transaction.id}`, '_blank');
                printWindow.focus();
                printWindow.print();
            }

            // Fungsi untuk memulai scanner
            // Fungsi untuk memulai scanner
            function startScanner() {
                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: scannerElement, // Elemen untuk menampilkan kamera
                        constraints: {
                            facingMode: "environment" // Gunakan kamera belakang
                        }
                    },
                    decoder: {
                        readers: ["code_128_reader", "ean_reader", "upc_reader"] // Format barcode yang didukung
                    }
                }, function (err) {
                    if (err) {
                        console.error("QuaggaJS error:", err);
                        return;
                    }
                    Quagga.start();
                });

                // Event ketika barcode terdeteksi
                Quagga.onDetected(function (data) {
                    const barcode = data.codeResult.code;
                    console.log("Barcode detected:", barcode);

                    // Cari produk berdasarkan barcode
                    const productCard = Array.from(document.querySelectorAll('.product-card')).find(card => card.dataset.barcode === barcode);

                    if (productCard) {
                        const id = productCard.dataset.id;
                        const name = productCard.dataset.name;
                        const price = parseFloat(productCard.dataset.price);
                        const stock = parseInt(productCard.dataset.stock);
                        const sizes = JSON.parse(productCard.dataset.sizes); // Data ukuran produk

                        // Jika produk memiliki ukuran
                        if (sizes.length > 0) {
                            let sizeOptions = sizes.map(size => `${size.name} (Stok: ${size.stock})`).join('\n');
                            let selectedSize = prompt(`Pilih ukuran untuk produk "${name}":\n${sizeOptions}`);

                            // Cari ukuran yang dipilih
                            const size = sizes.find(s => s.name.toLowerCase() === selectedSize?.toLowerCase());

                            if (!size) {
                                alert('Ukuran tidak valid atau stok tidak mencukupi.');
                                return;
                            }

                            // Periksa stok ukuran
                            if (size.stock <= 0) {
                                alert('Stok untuk ukuran ini tidak mencukupi.');
                                return;
                            }

                            // Tambahkan produk dengan ukuran ke keranjang
                            const existingItem = cart.find(item => item.id === id && item.size === size.name);

                            if (existingItem) {
                                if (existingItem.quantity < size.stock) {
                                    existingItem.quantity += 1;
                                } else {
                                    alert('Stok tidak mencukupi.');
                                }
                            } else {
                                cart.push({
                                    id,
                                    name,
                                    price,
                                    size: size.name,
                                    quantity: 1,
                                    stock: size.stock
                                });
                            }
                        } else {
                            // Jika produk tidak memiliki ukuran
                            const existingItem = cart.find(item => item.id === id);

                            if (existingItem) {
                                if (existingItem.quantity < stock) {
                                    existingItem.quantity += 1;
                                } else {
                                    alert('Stok tidak mencukupi.');
                                }
                            } else {
                                cart.push({
                                    id,
                                    name,
                                    price,
                                    quantity: 1,
                                    stock
                                });
                            }
                        }

                        updateCart();
                        alert(`Produk "${name}" berhasil ditambahkan ke keranjang.`);
                    } else {
                        alert('Produk dengan barcode ini tidak ditemukan');
                    }

                    // Hentikan scanner setelah barcode ditemukan
                    stopScanner();
                });
            }

            // Fungsi untuk menghentikan scanner
            function stopScanner() {
                Quagga.stop();
                scannerModal.classList.add('hidden');
            }

            // Event listener untuk tombol mulai scanner
            startScannerBtn.addEventListener('click', function () {
                scannerModal.classList.remove('hidden');
                startScanner();
            });

            // Event listener untuk tombol berhenti scanner
            stopScannerBtn.addEventListener('click', function () {
                stopScanner();
            });

            paymentAmountEl.addEventListener('input', function () {
                const total = parseFloat(totalEl.textContent.replace(/[^\d]/g, '')) || 0;
                const paymentMethod = paymentMethodEl.value;

                let change = 0;

                if (paymentMethod === '1') {
                    // Jika metode pembayaran tunai, hitung kembalian
                    const paymentAmount = parseFloat(this.value) || 0;
                    change = paymentAmount - total;
                } else if (paymentMethod === '2' || paymentMethod === '3') {
                    // Jika metode pembayaran QRIS atau transfer, paymentAmount = total
                    this.value = total;
                    change = 0;
                }

                // Tampilkan kembalian
                changeAmountEl.value = `Rp ${change.toLocaleString('id-ID')}`;
            });


        });
    </script>
@endsection