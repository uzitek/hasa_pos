<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || get_user_role($_SESSION['user_id']) != ROLE_ADMIN) {
    header("Location: login.php");
    exit();
}

$user = get_user_by_id($_SESSION['user_id']);

// Get low stock products
$low_stock_products = get_low_stock_products();

// Get out of stock products
$out_of_stock_products = get_out_of_stock_products();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Alerts - <?php echo COMPANY_NAME; ?> Inventory & POS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>
            
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Inventory Alerts</h1>
                </div>
                
                <h3>Low Stock Products</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Current Quantity</th>
                                <th>Reorder Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($low_stock_products as $product): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td><?php echo $product['reorder_level']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary restock-product" data-product-id="<?php echo $product['id']; ?>">Restock</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <h3 class="mt-5">Out of Stock Products</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Last Stocked Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($out_of_stock_products as $product): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo $product['last_stocked_date']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary restock-product" data-product-id="<?php echo $product['id']; ?>">Restock</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Restock Modal -->
    <div class="modal fade" id="restockModal" tabindex="-1" role="dialog" aria-labelledby="restockModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restockModalLabel">Restock Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="restockForm">
                    <div class="modal-body">
                        <input type="hidden" name="product_id" id="restock_product_id">
                        <div class="form-group">
                            <label for="restock_quantity">Quantity to Restock</label>
                            <input type="number" class="form-control" id="restock_quantity" name="restock_quantity" required min="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Restock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.restock-product').click(function() {
            var productId = $(this).data('product-id');
            $('#restock_product_id').val(productId);
            $('#restockModal').modal('show');
        });

        $('#restockForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'restock_product.php',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Product restocked successfully');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
    </script>
</body>
</html>