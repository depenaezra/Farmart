<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$cartBySeller = [];
if (!empty($cart)) {
    foreach ($cart as $row) {
        $sellerKey = (string) ($row['farmer_id'] ?? '0') . '|' . ($row['farmer_name'] ?? 'Seller');
        if (!isset($cartBySeller[$sellerKey])) {
            $cartBySeller[$sellerKey] = [
                'farmer_id' => $row['farmer_id'] ?? null,
                'farmer_name' => $row['farmer_name'] ?? 'Seller',
                'items' => [],
            ];
        }
        $cartBySeller[$sellerKey]['items'][] = $row;
    }
}
?>

<div class="max-w-[1280px] mx-auto px-4 sm:px-6 py-6 lg:py-8">
    <!-- Lazada-style top strip: title + search + cart count -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <nav class="text-sm text-slate-500 mb-1">
                <a href="/" class="hover:text-primary transition-colors">Home</a>
                <span class="mx-1.5">/</span>
                <span class="text-slate-800 font-medium">Shopping Cart</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Shopping Cart</h1>
            <p class="text-slate-600 text-sm mt-1"><?= (int) ($item_count ?? 0) ?> item<?= ((int) ($item_count ?? 0)) === 1 ? '' : 's' ?> in your cart</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto lg:min-w-[420px]">
            <form action="/marketplace" method="GET" class="flex flex-1 gap-0 rounded-xl overflow-hidden ring-1 ring-slate-200/90 shadow-sm bg-white focus-within:ring-2 focus-within:ring-primary/40 transition-shadow">
                <input type="text" name="keyword" value="<?= esc(request()->getGet('keyword') ?? '') ?>"
                       placeholder="Search products, farmers…"
                       class="flex-1 min-w-0 px-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 border-0 focus:ring-0">
                <button type="submit" class="shrink-0 px-5 py-2.5 bg-primary text-white font-semibold text-sm hover:bg-primary-hover transition-colors flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Search</span>
                </button>
            </form>
            <a href="/marketplace" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:border-primary/40 hover:text-primary transition-colors whitespace-nowrap">
                <i data-lucide="store" class="w-4 h-4"></i>
                Continue shopping
            </a>
        </div>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3" role="alert">
            <i data-lucide="check-circle" class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-green-800 font-semibold">Success</p>
                <p class="text-green-700 text-sm"><?= esc(session()->get('success')) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3" role="alert">
            <i data-lucide="alert-circle" class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-red-800 font-semibold">Error</p>
                <p class="text-red-700 text-sm"><?= esc(session()->get('error')) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
        <div class="farmart-card rounded-2xl p-12 sm:p-16 text-center max-w-lg mx-auto">
            <i data-lucide="shopping-cart" class="w-16 h-16 text-slate-300 mx-auto mb-4"></i>
            <h2 class="text-xl font-semibold text-slate-900 mb-2">Your cart is empty</h2>
            <p class="text-slate-600 mb-6 text-sm">Browse the marketplace and add fresh produce.</p>
            <form action="/marketplace" method="GET" class="flex flex-col sm:flex-row gap-2 max-w-md mx-auto mb-6">
                <input type="text" name="keyword" placeholder="Search the marketplace…" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-primary focus:border-transparent">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary-hover">Search</button>
            </form>
            <a href="/marketplace" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl hover:bg-primary-hover font-semibold text-sm shadow-md shadow-primary/25 transition-all">
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                Go to marketplace
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            <!-- Main cart column (~66%) -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                    <!-- Toolbar: select all + delete -->
                    <div class="flex flex-wrap items-center justify-between gap-3 px-4 sm:px-5 py-3.5 bg-slate-50/90 border-b border-slate-200/80">
                        <label class="inline-flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" id="cart-select-all" class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary">
                            <span class="text-sm font-semibold text-slate-800">Select all (<span id="cart-total-line-count"><?= count($cart) ?></span>)</span>
                        </label>
                        <button type="button" onclick="deleteSelectedItems()" class="text-sm font-semibold text-rose-600 hover:text-rose-700 hover:underline inline-flex items-center gap-1.5 px-2 py-1 rounded-lg hover:bg-rose-50 transition-colors">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            Delete
                        </button>
                    </div>

                    <form id="cartForm">
                        <?= csrf_field() ?>
                        <?php foreach ($cartBySeller as $sellerKey => $sellerBlock): ?>
                            <div class="seller-group border-b border-slate-100 last:border-b-0" data-seller-key="<?= esc($sellerKey, 'attr') ?>">
                                <!-- Seller header -->
                                <div class="flex items-center gap-3 px-4 sm:px-5 py-3 bg-gradient-to-r from-emerald-50/90 to-white border-l-4 border-primary">
                                    <input type="checkbox" class="cart-shop-select-all w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary" title="Select this seller">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold uppercase tracking-wider text-primary">Seller</p>
                                        <p class="font-semibold text-slate-900 truncate"><?= esc($sellerBlock['farmer_name']) ?></p>
                                    </div>
                                    <span class="hidden sm:inline-flex items-center gap-1 text-xs font-medium text-emerald-800 bg-emerald-100/80 px-2.5 py-1 rounded-full">
                                        <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                                        Direct from farmer
                                    </span>
                                </div>

                                <?php foreach ($sellerBlock['items'] as $item):
                                    $stock = isset($item['stock_quantity']) ? (int) $item['stock_quantity'] : 99;
                                    $maxQty = min(max($stock, 1), 99);
                                    $lineTotal = (float) $item['price'] * (int) $item['quantity'];
                                    ?>
                                    <div class="cart-row px-4 sm:px-5 py-4 flex flex-wrap sm:flex-nowrap gap-4 border-b border-slate-50 last:border-0"
                                         data-cart-row
                                         data-cart-id="<?= esc($item['id'], 'attr') ?>"
                                         data-unit-price="<?= esc($item['price'], 'attr') ?>"
                                         data-line-total="<?= esc($lineTotal, 'attr') ?>">
                                        <div class="flex items-start gap-3 sm:gap-4 w-full sm:w-auto">
                                            <div class="pt-1">
                                                <input type="checkbox"
                                                       name="selected_items[]"
                                                       value="<?= esc($item['id'], 'attr') ?>"
                                                       id="item_<?= esc($item['id'], 'attr') ?>"
                                                       class="cart-item-cb w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary"
                                                       onchange="updateCartTotal(); syncSelectAllState();">
                                            </div>
                                            <?php
                                            $cartImage = null;
                                            if (!empty($item['image_url'])) {
                                                $decoded = json_decode($item['image_url'], true);
                                                $cartImage = is_array($decoded) ? ($decoded[0] ?? null) : $item['image_url'];
                                            }
                                            ?>
                                            <a href="/marketplace/product/<?= esc($item['product_id'], 'attr') ?>" class="shrink-0 rounded-xl overflow-hidden ring-1 ring-slate-100">
                                                <?php if (!empty($cartImage)): ?>
                                                    <img src="<?= esc($cartImage) ?>" alt="" class="w-20 h-20 sm:w-24 sm:h-24 object-cover">
                                                <?php else: ?>
                                                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-slate-100 flex items-center justify-center">
                                                        <i data-lucide="image" class="w-8 h-8 text-slate-400"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </a>
                                            <div class="flex-1 min-w-0">
                                                <a href="/marketplace/product/<?= esc($item['product_id'], 'attr') ?>" class="font-semibold text-slate-900 hover:text-primary text-sm sm:text-base leading-snug line-clamp-2">
                                                    <?= esc($item['product_name']) ?>
                                                </a>
                                                <?php if (!empty($item['location'])): ?>
                                                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 shrink-0"></i>
                                                        <?= esc($item['location']) ?>
                                                    </p>
                                                <?php endif; ?>
                                                <?php if ($stock <= 10): ?>
                                                    <p class="text-xs font-medium text-rose-600 mt-1">Only <?= $stock ?> in stock — order soon</p>
                                                <?php endif; ?>
                                                <div class="flex flex-wrap items-center gap-2 mt-3">
                                                    <div class="inline-flex items-center rounded-xl border border-slate-200 bg-white shadow-sm">
                                                        <button type="button" class="px-3 py-2 text-slate-600 hover:bg-slate-50 rounded-l-xl transition-colors disabled:opacity-40" aria-label="Decrease"
                                                                onclick="adjustQuantity('<?= esc($item['id'], 'js') ?>', -1)">
                                                            <i data-lucide="minus" class="w-4 h-4"></i>
                                                        </button>
                                                        <input type="number"
                                                               id="quantity_<?= esc($item['id'], 'attr') ?>"
                                                               value="<?= (int) $item['quantity'] ?>"
                                                               data-original-quantity="<?= (int) $item['quantity'] ?>"
                                                               min="1"
                                                               max="<?= $maxQty ?>"
                                                               readonly
                                                               class="w-12 text-center text-sm font-semibold border-x border-slate-200 py-2 bg-slate-50/50 text-slate-900 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                                                        <button type="button" class="px-3 py-2 text-slate-600 hover:bg-slate-50 rounded-r-xl transition-colors disabled:opacity-40" aria-label="Increase"
                                                                onclick="adjustQuantity(<?= (int) $item['id'] ?>, 1)">
                                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                                        </button>
                                                    </div>
                                                    <span class="text-xs text-slate-500"><?= esc($item['unit']) ?></span>
                                                    <button type="button" onclick="removeFromCart(<?= (int) $item['id'] ?>)" class="ml-auto sm:ml-2 p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Remove">
                                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Price column (Lazada-style right stack) -->
                                        <div class="w-full sm:w-36 sm:ml-auto flex sm:flex-col sm:items-end justify-between sm:justify-start gap-2 pt-1 sm:pt-0 border-t sm:border-t-0 border-slate-100 sm:border-0">
                                            <div class="text-left sm:text-right">
                                                <p class="js-line-total text-lg font-bold text-primary tabular-nums">₱<?= number_format($lineTotal, 2) ?></p>
                                                <p class="js-unit-label text-xs text-slate-500 tabular-nums">₱<?= number_format((float) $item['price'], 2) ?> / <?= esc($item['unit']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </form>

                    <div class="px-4 sm:px-5 py-3 bg-slate-50/80 border-t border-slate-200/80 flex justify-end">
                        <button type="button" onclick="clearCart()" class="text-sm font-medium text-slate-600 hover:text-rose-600 inline-flex items-center gap-2 transition-colors">
                            <i data-lucide="eraser" class="w-4 h-4"></i>
                            Clear entire cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Order summary sidebar (~33%) -->
            <aside class="lg:col-span-4 lg:sticky lg:top-24 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-br from-slate-50 to-white">
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i>
                            Order summary
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">Delivery address &amp; fees are confirmed at checkout.</p>
                    </div>
                    <div class="p-5 space-y-4" id="cart-order-summary">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Subtotal (<span id="summary-selected-count">0</span> items)</span>
                            <span class="font-semibold text-slate-900 tabular-nums" id="summary-subtotal">₱0.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Shipping</span>
                            <span class="font-medium text-emerald-700 text-xs sm:text-sm">Set at checkout</span>
                        </div>
                        <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 p-3">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Voucher</label>
                            <div class="flex gap-2 mt-2">
                                <input type="text" disabled placeholder="Coming soon" class="flex-1 text-sm px-3 py-2 rounded-lg border border-slate-200 bg-white text-slate-400 cursor-not-allowed">
                                <button type="button" disabled class="px-3 py-2 rounded-lg bg-slate-200 text-slate-500 text-xs font-semibold cursor-not-allowed">Apply</button>
                            </div>
                        </div>
                        <div class="border-t border-slate-200 pt-4 flex justify-between items-baseline">
                            <span class="text-slate-700 font-semibold">Total</span>
                            <span class="text-2xl font-bold text-primary tabular-nums" id="summary-total">₱0.00</span>
                        </div>
                        <button type="button" onclick="proceedToCheckout()" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-primary to-emerald-800 text-white font-bold text-sm sm:text-base shadow-lg shadow-primary/30 hover:from-primary-hover hover:to-emerald-900 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                            PROCEED TO CHECKOUT
                        </button>
                        <a href="/marketplace" class="block text-center text-sm font-semibold text-primary hover:text-primary-hover py-1">
                            Continue shopping
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') lucide.createIcons();
    initializeCart();
});

function initializeCart() {
    document.querySelectorAll('.cart-item-cb').forEach(function(cb) {
        cb.addEventListener('change', function() {
            updateCartTotal();
            syncSelectAllState();
        });
    });
    var master = document.getElementById('cart-select-all');
    if (master) {
        master.addEventListener('change', function() {
            selectAllItems(master.checked);
            updateCartTotal();
        });
    }
    document.querySelectorAll('.cart-shop-select-all').forEach(function(shopCb) {
        shopCb.addEventListener('change', function() {
            var block = shopCb.closest('.seller-group');
            if (!block) return;
            block.querySelectorAll('.cart-item-cb').forEach(function(i) {
                i.checked = shopCb.checked;
            });
            updateCartTotal();
            syncSelectAllState();
        });
    });
    updateCartTotal();
    syncSelectAllState();
}

function syncSelectAllState() {
    var all = document.querySelectorAll('.cart-item-cb');
    var master = document.getElementById('cart-select-all');
    if (!master || !all.length) return;
    var total = all.length;
    var checked = 0;
    all.forEach(function(c) { if (c.checked) checked++; });
    master.checked = checked === total && total > 0;
    master.indeterminate = checked > 0 && checked < total;

    document.querySelectorAll('.seller-group').forEach(function(block) {
        var cbs = block.querySelectorAll('.cart-item-cb');
        var shopMaster = block.querySelector('.cart-shop-select-all');
        if (!shopMaster || !cbs.length) return;
        var sc = 0;
        cbs.forEach(function(c) { if (c.checked) sc++; });
        shopMaster.checked = sc === cbs.length;
        shopMaster.indeterminate = sc > 0 && sc < cbs.length;
    });
}

function updateRowLineTotal(row) {
    var unit = parseFloat(row.getAttribute('data-unit-price')) || 0;
    var input = row.querySelector('input[id^="quantity_"]');
    var qty = input ? parseInt(input.value, 10) || 0 : 0;
    var line = unit * qty;
    row.setAttribute('data-line-total', line);
    var el = row.querySelector('.js-line-total');
    if (el) el.textContent = '₱' + line.toFixed(2);
}

function updateCartTotal() {
    var total = 0;
    var count = 0;
    document.querySelectorAll('.cart-item-cb:checked').forEach(function(cb) {
        var row = cb.closest('[data-cart-row]');
        if (!row) return;
        updateRowLineTotal(row);
        total += parseFloat(row.getAttribute('data-line-total')) || 0;
        count++;
    });
    var subEl = document.getElementById('summary-subtotal');
    var totEl = document.getElementById('summary-total');
    var cntEl = document.getElementById('summary-selected-count');
    if (subEl) subEl.textContent = '₱' + total.toFixed(2);
    if (totEl) totEl.textContent = '₱' + total.toFixed(2);
    if (cntEl) cntEl.textContent = String(count);
}

function selectAllItems(checked) {
    document.querySelectorAll('.cart-item-cb').forEach(function(cb) {
        cb.checked = checked;
    });
    document.querySelectorAll('.cart-shop-select-all').forEach(function(s) {
        s.checked = checked;
        s.indeterminate = false;
    });
}

function adjustQuantity(cartItemId, delta) {
    var input = document.getElementById('quantity_' + cartItemId);
    if (!input) return;
    var max = parseInt(input.getAttribute('max'), 10) || 99;
    var v = parseInt(input.value, 10) + delta;
    if (v < 1) v = 1;
    if (v > max) v = max;
    if (v === parseInt(input.value, 10)) return;
    input.value = v;
    updateQuantity(cartItemId);
}

function deleteSelectedItems() {
    var ids = Array.from(document.querySelectorAll('.cart-item-cb:checked')).map(function(c) { return c.value; });
    if (!ids.length) {
        Swal.fire({ icon: 'warning', title: 'Nothing selected', text: 'Select items to remove.', confirmButtonColor: '#166534' });
        return;
    }
    Swal.fire({
        title: 'Remove selected?',
        text: 'Remove ' + ids.length + ' item(s) from your cart?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#b91c1c',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, remove',
        cancelButtonText: 'Cancel'
    }).then(function(result) {
        if (!result.isConfirmed) return;
        Swal.fire({ title: 'Removing…', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
        var tokenName = '<?= csrf_token() ?>';
        var token = document.querySelector('input[name="' + tokenName + '"]');
        var val = token ? token.value : '';
        Promise.all(ids.map(function(id) {
            return fetch('/cart/remove/' + id, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: new URLSearchParams((function() { var o = {}; o[tokenName] = val; return o; })())
            }).then(function(r) { return r.json(); });
        })).then(function() {
            Swal.close();
            window.location.reload();
        }).catch(function() {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Could not remove some items.', confirmButtonColor: '#166534' });
        });
    });
}

function removeFromCart(cartItemId) {
    var input = document.getElementById('quantity_' + cartItemId);
    if (!input) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Could not find item', confirmButtonColor: '#166534' });
        return;
    }
    var row = input.closest('[data-cart-row]');
    var titleEl = row.querySelector('a.font-semibold');
    var productName = titleEl ? titleEl.textContent.trim() : 'Item';
    var qty = parseInt(input.value, 10) || 0;
    var unit = parseFloat(row.getAttribute('data-unit-price')) || 0;
    var itemTotal = unit * qty;

    Swal.fire({
        title: 'Remove item?',
        text: productName + '\nQty: ' + qty + ' · Line total: ₱' + itemTotal.toFixed(2),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#b91c1c',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Remove',
        cancelButtonText: 'Keep'
    }).then(function(result) {
        if (!result.isConfirmed) return;
        var tokenName = '<?= csrf_token() ?>';
        var csrfToken = document.querySelector('input[name="' + tokenName + '"]').value;
        fetch('/cart/remove/' + cartItemId, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: new URLSearchParams((function() { var o = {}; o[tokenName] = csrfToken; return o; })())
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Removed', timer: 1200, showConfirmButton: false }).then(function() { location.reload(); });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed', confirmButtonColor: '#166534' });
            }
        })
        .catch(function() {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed.', confirmButtonColor: '#166534' });
        });
    });
}

function clearCart() {
    Swal.fire({
        title: 'Clear entire cart?',
        text: 'This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#b91c1c',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Clear all'
    }).then(function(result) {
        if (!result.isConfirmed) return;
        Swal.fire({ title: 'Clearing…', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
        fetch('/cart/clear', { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) {
            if (r.ok) {
                Swal.fire({ icon: 'success', title: 'Cart cleared', confirmButtonColor: '#166534' }).then(function() { location.reload(); });
            } else throw new Error('fail');
        })
        .catch(function() {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to clear cart.', confirmButtonColor: '#166534' });
        });
    });
}

function proceedToCheckout() {
    var form = document.getElementById('cartForm');
    var fd = new FormData(form);
    var selected = fd.getAll('selected_items[]');
    if (!selected.length) {
        Swal.fire({ icon: 'warning', title: 'Select items', text: 'Choose at least one product to checkout.', confirmButtonColor: '#166534' });
        return;
    }
    form.action = '/checkout';
    form.method = 'POST';
    form.submit();
}

function updateQuantity(cartItemId) {
    var quantityInput = document.getElementById('quantity_' + cartItemId);
    var newQuantity = parseInt(quantityInput.value, 10);
    if (newQuantity < 1 || newQuantity > 99 || isNaN(newQuantity)) {
        Swal.fire({ icon: 'warning', title: 'Invalid quantity', text: 'Use a quantity between 1 and 99.', confirmButtonColor: '#166534' });
        return;
    }
    var row = quantityInput.closest('[data-cart-row]');
    var unit = parseFloat(row.getAttribute('data-unit-price')) || 0;
    var priceEl = row.querySelector('.js-line-total');
    priceEl.textContent = '₱' + (unit * newQuantity).toFixed(2);
    row.setAttribute('data-line-total', unit * newQuantity);

    var tokenName = '<?= csrf_token() ?>';
    var csrfToken = document.querySelector('input[name="' + tokenName + '"]').value;

    fetch('/cart/update/' + cartItemId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: new URLSearchParams((function() { var o = {}; o[tokenName] = csrfToken; o.quantity = newQuantity; return o; })())
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            quantityInput.setAttribute('data-original-quantity', newQuantity);
            updateCartTotal();
            syncSelectAllState();
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'success', title: 'Updated', timer: 900, showConfirmButton: false });
            }
        } else {
            var oldQ = parseInt(quantityInput.getAttribute('data-original-quantity'), 10) || 1;
            quantityInput.value = oldQ;
            updateRowLineTotal(row);
            updateCartTotal();
            Swal.fire({ icon: 'error', title: 'Update failed', text: data.message || 'Try again.', confirmButtonColor: '#166534' });
        }
    })
    .catch(function() {
        var oldQ = parseInt(quantityInput.getAttribute('data-original-quantity'), 10) || 1;
        quantityInput.value = oldQ;
        updateRowLineTotal(row);
        updateCartTotal();
        Swal.fire({ icon: 'error', title: 'Error', text: 'Network error.', confirmButtonColor: '#166534' });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    <?php if (session()->has('success') && stripos((string) session()->get('success'), 'order') !== false): ?>
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Checkout successful!',
                text: <?= json_encode((string) session()->get('success'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
                confirmButtonText: 'Great',
                confirmButtonColor: '#166534'
            });
        }
        setTimeout(function() { window.location.reload(); }, 1600);
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>
