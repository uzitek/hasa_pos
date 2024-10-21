<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/index.php') ? 'active' : ''; ?>" href="index.php">
                    <?php echo __('dashboard', DEFAULT_LANG); ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/inventory.php') ? 'active' : ''; ?>" href="inventory.php">
                    <?php echo __('inventory', DEFAULT_LANG); ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/pos.php') ? 'active' : ''; ?>" href="pos.php">
                    <?php echo __('point_of_sale', DEFAULT_LANG); ?>
                </a>
            </li>
            <?php if (get_user_role($_SESSION['user_id']) == ROLE_ADMIN): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/reports.php') ? 'active' : ''; ?>" href="reports.php">
                    <?php echo __('reports', DEFAULT_LANG); ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/users.php') ? 'active' : ''; ?>" href="users.php">
                    <?php echo __('users', DEFAULT_LANG); ?>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>