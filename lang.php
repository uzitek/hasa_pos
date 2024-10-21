<?php
$lang = [
    'en' => [
        'welcome' => 'Welcome',
        'logout' => 'Logout',
        'dashboard' => 'Dashboard',
        'inventory' => 'Inventory',
        'point_of_sale' => 'Point of Sale',
        'reports' => 'Reports',
        'users' => 'Users',
        'designed_by' => 'Designed by',
        'add_product' => 'Add Product',
        'edit_product' => 'Edit Product',
        'delete_product' => 'Delete Product',
        'confirm_delete' => 'Are you sure you want to delete this item?',
        'save' => 'Save',
        'cancel' => 'Cancel',
        // Add more translations as needed
    ],
    'es' => [
        'welcome' => 'Bienvenido',
        'logout' => 'Cerrar sesión',
        'dashboard' => 'Panel de control',
        'inventory' => 'Inventario',
        'point_of_sale' => 'Punto de Venta',
        'reports' => 'Informes',
        'users' => 'Usuarios',
        'designed_by' => 'Diseñado por',
        'add_product' => 'Añadir Producto',
        'edit_product' => 'Editar Producto',
        'delete_product' => 'Eliminar Producto',
        'confirm_delete' => '¿Estás seguro de que quieres eliminar este artículo?',
        'save' => 'Guardar',
        'cancel' => 'Cancelar',
        // Add more translations as needed
    ],
];

function __($key, $lang_code = DEFAULT_LANG) {
    global $lang;
    return $lang[$lang_code][$key] ?? $key;
}
?>