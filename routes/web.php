<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\BranchController;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\RequestController;
use \App\Http\Controllers\StockController;
use \App\Http\Controllers\TransferController;
use \App\Http\Controllers\UserController;

Route::get('generate', function (){
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'ok';
});

Route::get('database_migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        echo 'Migrations have run successfully.';
    } catch (\Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
});

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function() { 
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/edit/{product}', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/update/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/destroy', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('products/view/{product}', [ProductController::class, 'view'])->name('products.view');

    Route::get('branches', [BranchController::class, 'index'])->name('branches.index');
    Route::get('branches/create', [BranchController::class, 'create'])->name('branches.create');
    Route::post('branches/store', [BranchController::class, 'store'])->name('branches.store');
    Route::get('branches/edit/{branch}', [BranchController::class, 'edit'])->name('branches.edit');
    Route::put('branches/update/{branch}', [BranchController::class, 'update'])->name('branches.update');
    Route::delete('branches/destroy', [BranchController::class, 'destroy'])->name('branches.destroy');
    Route::get('branches/view/{branch}', [BranchController::class, 'view'])->name('branches.view');

    Route::get('update_stocks', [StockController::class, 'index'])->name('update_stocks.index');
    Route::get('update_stocks/create', [StockController::class, 'create'])->name('update_stocks.create');
    Route::post('update_stocks/store', [StockController::class, 'store'])->name('update_stocks.store');
    Route::get('update_stocks/edit/{update_stock}', [StockController::class, 'edit'])->name('update_stocks.edit');
    Route::put('update_stocks/update/{update_stock}', [StockController::class, 'update'])->name('update_stocks.update');
    Route::delete('update_stocks/destroy', [StockController::class, 'destroy'])->name('update_stocks.destroy');
    Route::get('update_stocks/view/{update_stock}', [StockController::class, 'view'])->name('update_stocks.view');
    Route::get('reports', [StockController::class, 'reports'])->name('reports.index'); 
    Route::get('/product-info/{product_id}', [StockController::class, 'getProductInfo']);

    Route::get('inventory_requests', [RequestController::class, 'index'])->name('inventory_requests.index');
    Route::get('inventory_requests/create', [RequestController::class, 'create'])->name('inventory_requests.create');
    Route::post('inventory_requests/store', [RequestController::class, 'store'])->name('inventory_requests.store');
    Route::get('inventory_requests/edit/{inventory_request}', [RequestController::class, 'edit'])->name('inventory_requests.edit');
    Route::put('inventory_requests/update/{inventory_request}', [RequestController::class, 'update'])->name('inventory_requests.update');
    Route::delete('inventory_requests/destroy', [RequestController::class, 'destroy'])->name('inventory_requests.destroy');
    Route::get('inventory_requests/view/{inventory_request}', [RequestController::class, 'view'])->name('inventory_requests.view');
    Route::get('/get-branch-products/{product_id}', [RequestController::class, 'getBranchProducts']);

    Route::get('transfers', [TransferController::class, 'index'])->name('transfers.index');
    Route::get('transfers/approve/{to_approve}', [TransferController::class, 'approve'])->name('transfers.approve');
    Route::get('transfers/reject/{to_approve}', [TransferController::class, 'reject'])->name('transfers.reject');
    Route::get('transfers/view/{transfer}', [TransferController::class, 'view'])->name('transfers.view');
    Route::put('transfers/fulfill/{transfer}', [TransferController::class, 'fulfill'])->name('transfers.fulfill');
    Route::delete('transfers/fulfillment/destroy', [TransferController::class, 'destroy'])->name('fulfillment.destroy');

    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/email/check', [UserController::class, 'emailcheck'])->name('users.emailcheck');
    Route::get('/users/contact/check', [UserController::class, 'contactcheck'])->name('users.contactcheck');
    Route::get('users', [UserController::class, 'index'])->name('users.index');         
    Route::get('users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('users/{user}/view', [UserController::class, 'view'])->name('users.view');

    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile.index');
    Route::put('/profile', [AuthController::class, 'update'])->name('password.update');
});