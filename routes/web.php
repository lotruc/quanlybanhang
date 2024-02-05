<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\Website\CartController;
use App\Http\Controllers\Website\CheckoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\ShopController;
use App\Http\Controllers\Website\ContactController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//route danh cho nguoi dung
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/searchProductHome', [HomeController::class, 'searchProductByCategory'])->name('website.home.searchProductByCategory');

Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactController::class, 'showContact'])->name('website.contact.index');
    Route::post('/create', [ContactController::class, 'create'])->name('contact.create');
});
Route::prefix('product')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('website.product.index');
    Route::post('/search', [ShopController::class, 'search'])->name('website.product.search');
    Route::get('/details/{id}', [ShopController::class, 'details'])->name('website.product.details');
});

// route danh cho admin
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::prefix('category')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('admin.category.index');
        Route::post('/search', [AdminCategoryController::class, 'search'])->name('admin.category.search');
        Route::post('/create', [AdminCategoryController::class, 'create'])->name('admin.category.create');
        Route::post('/update', [AdminCategoryController::class, 'update'])->name('admin.category.update');
        Route::delete('/delete/{id}', [AdminCategoryController::class, 'delete'])->name('admin.category.delete');
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('admin.product.index');
        Route::post('/search', [AdminProductController::class, 'search'])->name('admin.product.search');
        Route::post('/create', [AdminProductController::class, 'create'])->name('admin.product.create');
        Route::post('/update', [AdminProductController::class, 'update'])->name('admin.product.update');
        Route::delete('/delete/{id}', [AdminProductController::class, 'delete'])->name('admin.product.delete');
    });

    Route::prefix('post')->group(function () {
        Route::get('/', [AdminPostController::class, 'index'])->name('admin.post.index');
        Route::post('/search', [AdminPostController::class, 'search'])->name('admin.post.search');
        Route::post('/create', [AdminPostController::class, 'create'])->name('admin.post.create');
        Route::post('/update', [AdminPostController::class, 'update'])->name('admin.post.update');
        Route::delete('/delete/{id}', [AdminPostController::class, 'delete'])->name('admin.post.delete');
    });
    Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');

    Route::prefix('contacts')->group(function () {
        Route::get('/', [AdminContactController::class, 'index'])->name('admin.contact.index');
        Route::post('/search', [AdminContactController::class, 'search'])->name('admin.contact.search');
        Route::delete('/delete/{id}', [AdminContactController::class, 'delete'])->name('admin.contact.delete');
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('admin.order.index');
        Route::post('/search', [AdminOrderController::class, 'search'])->name('admin.order.search');
        Route::post('/updateStatus', [AdminOrderController::class, 'updateStatus'])->name('admin.order.updateStatus');
        Route::delete('/delete/{id}', [AdminOrderController::class, 'delete'])->name('admin.order.delete');
    });

    Route::prefix('orders/details')->group(function () {
        Route::get('/{id}', [AdminOrderController::class, 'details'])->name('admin.order.details');
        Route::post('/search', [AdminOrderController::class, 'searchDetails'])->name('admin.order.searchDetails');
    });
});




// route cho nguoi dung dang nhap
Route::middleware('auth')->group(function () {
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/', [CartController::class, 'search'])->name('cart.search');
        Route::post('/getTotalProductInCart', [CartController::class, 'getTotalProductInCart'])->name('cart.getTotalProductInCart');
        Route::post('/add_to_cart', [CartController::class, 'addToCart'])->name('cart.add');
        Route::post('/update_cart', [CartController::class, 'updateCart'])->name('cart.update');
        Route::delete('/remove', [CartController::class, 'removeProductFromCart'])->name('cart.remove');
    });

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/placeOrder', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');


});

require __DIR__ . '/auth.php';
