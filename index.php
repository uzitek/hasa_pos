<?php
require_once 'config/config.php';
require_once 'src/functions.php';
require_once 'src/error_logger.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user = get_user_by_id($_SESSION['user_id']);

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?php echo __('dashboard', DEFAULT_LANG); ?></h1>
    </div>
    
    <!-- Add your dashboard content here -->
    
</main>

<?php include 'includes/footer.php'; ?>