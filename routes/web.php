<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DeliveryController as AdminDeliveryController;
use App\Http\Controllers\Admin\DeliveryZoneController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\OrderStatusHistoryController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Customer\SettingsController;
use App\Http\Controllers\Delivery\DashboardController as DeliveryDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'km'])) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('lang.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{id}', [ProductController::class, 'category'])->name('products.category');
Route::get('/search', [ProductController::class, 'search'])->name('products.search')->middleware('throttle:60,1');
Route::get('/search-suggestions', [ProductController::class, 'suggestions'])->name('products.suggestions')->middleware('throttle:60,1');

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/my-orders', [OrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('customer.orders.show');
    Route::patch('/my-orders/{id}/cancel', [OrderController::class, 'cancel'])->name('customer.orders.cancel');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'customerProfile'])->name('profile.index');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'customerUpdate'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
    Route::put('/preferences', [SettingsController::class, 'updatePreferences'])->name('preferences.update');
    Route::put('/language', [SettingsController::class, 'updateLanguage'])->name('language.update');
    Route::put('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
    Route::put('/payment', [SettingsController::class, 'updatePayment'])->name('payment.update');
    Route::put('/privacy', [SettingsController::class, 'updatePrivacy'])->name('privacy.update');
    Route::put('/accessibility', [SettingsController::class, 'updateAccessibility'])->name('accessibility.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::patch('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.set_default');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($user->role === 'delivery') {
        return redirect()->route('delivery.dashboard');
    }

    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/products/{id}/reviews', [ProductReviewController::class, 'store'])->name('products.reviews.store');
});

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send')->middleware('throttle:5,1');

Route::get('/promotions', [ProductController::class, 'promotions'])->name('promotions.index');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', AdminProductController::class);
    Route::resource('customers', CustomerController::class)->only(['index', 'show']);
    Route::patch('/customers/{id}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle_status');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low_stock');
    Route::get('/inventory/{id}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::get('/inventory/{id}/stock-in', [InventoryController::class, 'stockIn'])->name('inventory.stock_in');
    Route::post('/inventory/{id}/stock-in', [InventoryController::class, 'processStockIn'])->name('inventory.process_stock_in');
    Route::get('/inventory/{id}/stock-out', [InventoryController::class, 'stockOut'])->name('inventory.stock_out');
    Route::post('/inventory/{id}/stock-out', [InventoryController::class, 'processStockOut'])->name('inventory.process_stock_out');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update_status');
    Route::patch('/orders/{id}/assign-delivery', [AdminOrderController::class, 'assignDelivery'])->name('orders.assign_delivery');
    Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.print_invoice');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::patch('/payments/{id}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::patch('/payments/{id}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
    Route::patch('/payments/{id}/cod-paid', [PaymentController::class, 'markCodPaid'])->name('payments.mark_cod_paid');

    Route::get('/deliveries', [AdminDeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('/deliveries/create', [AdminDeliveryController::class, 'create'])->name('deliveries.create');
    Route::post('/deliveries', [AdminDeliveryController::class, 'store'])->name('deliveries.store');
    Route::get('/deliveries/{id}', [AdminDeliveryController::class, 'show'])->name('deliveries.show');
    Route::patch('/deliveries/{id}/tracking', [AdminDeliveryController::class, 'updateTracking'])->name('deliveries.update_tracking');
    Route::patch('/deliveries/{id}/status', [AdminDeliveryController::class, 'updateStatus'])->name('deliveries.update_status');
    Route::patch('/deliveries/{id}/failed-reason', [AdminDeliveryController::class, 'updateFailedReason'])->name('deliveries.update_failed_reason');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock_movements.index');
    Route::get('/order-status-histories/{order}', [OrderStatusHistoryController::class, 'index'])->name('order_status_histories.index');
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::post('/purchase-orders/{id}/receive', [PurchaseOrderController::class, 'receiveStock'])->name('purchase_orders.receive');
    Route::post('/purchase-orders/{id}/update-status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase_orders.update_status');
    Route::resource('coupons', CouponController::class);
    Route::resource('banners', BannerController::class);
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::resource('users', UserController::class);
    Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle_status');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{id}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::patch('/reviews/{id}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{id}/hide', [ReviewController::class, 'hide'])->name('reviews.hide');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('admin_notifications.index');
    Route::post('/notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('admin_notifications.mark_read');
    Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllRead'])->name('admin_notifications.mark_all_read');
    Route::delete('/notifications/{id}', [AdminNotificationController::class, 'destroy'])->name('admin_notifications.destroy');
    Route::post('/notifications/send', [AdminNotificationController::class, 'send'])->name('admin_notifications.send');

    Route::resource('delivery-zones', DeliveryZoneController::class);
    Route::patch('/delivery-zones/{id}/toggle-status', [DeliveryZoneController::class, 'toggleStatus'])->name('delivery-zones.toggle_status');

    Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
    Route::get('/exports/orders', [ExportController::class, 'exportOrders'])->name('exports.orders');
    Route::get('/exports/customers', [ExportController::class, 'exportCustomers'])->name('exports.customers');
    Route::get('/exports/products', [ExportController::class, 'exportProducts'])->name('exports.products');
    Route::get('/exports/payments', [ExportController::class, 'exportPayments'])->name('exports.payments');
    Route::get('/exports/report', [ExportController::class, 'exportReport'])->name('exports.report');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark_read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.mark_all_read');
});

Route::middleware(['auth', 'role:delivery'])->prefix('delivery')->name('delivery.')->group(function () {
    Route::get('/dashboard', [DeliveryDashboardController::class, 'index'])->name('dashboard');
    Route::get('/{id}', [DeliveryDashboardController::class, 'show'])->name('show');
    Route::patch('/{id}/status', [DeliveryDashboardController::class, 'updateStatus'])->name('update_status');
});

require __DIR__.'/auth.php';
