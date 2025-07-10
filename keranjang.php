<?php
// File: keranjang.php (with Item List in Summary)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Logic for updating or removing items from the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantities'] as $product_id => $quantity) {
            $quantity = (int)$quantity;
            if ($quantity > 0) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
    
    if (isset($_POST['remove_item'])) {
        $product_id_to_remove = $_POST['remove_item'];
        unset($_SESSION['cart'][$product_id_to_remove]);
    }

    header('Location: keranjang.php');
    exit();
}

require_once 'includes/header.php';

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = 0;
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Keranjang Belanja Anda</h1>
        </div>
    </div>
    
    <?php if (!empty($cart_items)): ?>
        <div class="row">
            <!-- Cart Items Column -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="keranjang.php" method="post" id="cart-form">
                            <?php foreach ($cart_items as $id => $item): ?>
                                <?php
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total_price += $subtotal;
                                ?>
                                <div class="row mb-4 border-bottom pb-4">
                                    <div class="col-md-2">
                                        <img src="uploads/products/<?php echo htmlspecialchars($item['image_url']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="mt-2 mt-md-0"><?php echo htmlspecialchars($item['name']); ?></h5>
                                        <p class="text-muted mb-0">Harga: Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="quantity-<?php echo $id; ?>" class="form-label d-md-none">Jumlah:</label>
                                        <input type="number" id="quantity-<?php echo $id; ?>" name="quantities[<?php echo $id; ?>]" value="<?php echo $item['quantity']; ?>" class="form-control text-center" min="1">
                                    </div>
                                    <div class="col-md-2 text-md-end">
                                        <p class="mb-0 mt-2 mt-md-0"><span class="d-md-none">Subtotal: </span><strong>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></strong></p>
                                    </div>
                                    <div class="col-md-1 text-md-center">
                                         <button type="submit" name="remove_item" value="<?php echo $id; ?>" class="btn btn-outline-danger btn-sm mt-2 mt-md-0" onclick="return confirm('Anda yakin ingin menghapus item ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </form>
                    </div>
                </div>
                <a href="produk.php" class="btn btn-link text-secondary mt-3"><i class="bi bi-arrow-left me-2"></i> Lanjut Belanja</a>
            </div>

            <!-- ============================================= -->
            <!-- [MODIFIED] Summary Column -->
            <!-- ============================================= -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Ringkasan</h4>
                        
                        <!-- [NEW] Loop to display items in summary -->
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach($cart_items as $item): ?>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <small><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</small>
                                    <small>Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="d-flex justify-content-between">
                            <p class="text-muted">Subtotal</p>
                            <p>Rp <?php echo number_format($total_price, 0, ',', '.'); ?></p>
                        </div>
                        <!-- <div class="d-flex justify-content-between">
                            <p class="text-muted">Ongkos Kirim</p>
                            <p>Akan dihitung</p>
                        </div> -->
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <p>Total</p>
                            <p>Rp <?php echo number_format($total_price, 0, ',', '.'); ?></p>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <!-- This button will submit the form on the left -->
                            <button type="submit" name="update_cart" class="btn btn-outline-secondary" form="cart-form">
                                <i class="bi bi-arrow-repeat me-1"></i> Update Keranjang
                            </button>
                            <a href="form_pemesanan.php" class="btn text-white btn-lg" style="background-color: #fd7e14;">
                                Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Your visually appealing "Empty Cart" display -->
        <div class="row justify-content-center mt-5">
            <div class="col-lg-7">
                <div class="card text-center border-0" style="background-color: #f8f9fa;">
                    <div class="card-body p-5">
                        <i class="bi bi-cart-x text-muted" style="font-size: 5rem;"></i>
                        <h2 class="card-title mt-3">Keranjang Anda Masih Kosong</h2>
                        <p class="card-text text-muted">
                            Sepertinya Anda belum menambahkan produk apapun. Mari jelajahi produk unggulan kami!
                        </p>
                        <a href="produk.php" class="btn btn-lg mt-3 text-white" style="background-color: #fd7e14;">
                           <i class="bi bi-box-seam me-2"></i> Jelajahi Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
require_once 'includes/footer.php';
?>