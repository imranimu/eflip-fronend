<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\ContestCategoryController;
use App\Http\Controllers\admin\EssayContestController;
use Illuminate\Support\Facades\Route;


// Route::group(['middleware'=>'checkAdmin','prefix'=>'admin/'],function() {
    Route::get('/profile',[AdminController::class,'adminProfile']);
    Route::post('/profile/update',[AdminController::class,'updateProfile']);
    Route::get('/dashboard', [AdminController::class,'index'])->name('dashboard');

    Route::get('/contest/categories',[ContestCategoryController::class,'categories']);
    Route::post('/contest/categories/add',[ContestCategoryController::class,'addCategory']);
    Route::get('/contest/category/edit/{id}',[ContestCategoryController::class,'editCategory']);
    Route::post('/contest/category/update',[ContestCategoryController::class,'updateCategory']);
    Route::get('/contest/category/delete/{id}',[ContestCategoryController::class,'deleteCategory']);

    
    Route::get('/contest/essays',[EssayContestController::class,'index']);
    Route::get('/contest/essay/winner/{id}',[EssayContestController::class,'makeWinner']);

    // Route::get('/categories',[CategoryController::class,'categories']);
    // Route::post('/categories/add',[CategoryController::class,'addCategory']);
    // Route::get('/category/edit/{id}',[CategoryController::class,'editCategory']);
    // Route::post('/category/update',[CategoryController::class,'updateCategory']);
    // Route::get('/sub-category/edit/{id}',[CategoryController::class,'editSubCategory']);
    // Route::post('/sub-category/update',[CategoryController::class,'updateSubCategory']);
    // Route::get('/category/activate/{id}',[CategoryController::class,'activateCategory']);
    // Route::get('/category/deactivate/{id}',[CategoryController::class,'deactivateCategory']);
    // Route::get('/category/delete/{id}',[CategoryController::class,'deleteCategory']);
    // Route::get('/sub-category/delete/{id}',[CategoryController::class,'deleteSubCategory']);
    // Route::post('/sub-categories/add',[CategoryController::class,'addSubCategory']);


    // Route::get('/products', [ProductController::class,'productLists']);
    // Route::get('/product/add', [ProductController::class,'productAdd']);
    // Route::get('/product/trash', [ProductController::class,'trashedProduct']);
    // Route::post('/product/store', [ProductController::class,'productStore']);
    // Route::get('/product/edit/{id}', [ProductController::class,'productEdit']);
    // Route::get('/product/trash/{id}', [ProductController::class,'productTrash']);
    // Route::get('/product/restore/{id}', [ProductController::class,'restoreProduct']);
    // Route::get('/product/delete/{id}', [ProductController::class,'deleteProduct']);
    // Route::get('/product/image/del/{id}', [ProductController::class,'deleteProductImage']);
    // Route::post('/product/update', [ProductController::class,'productUpdate']);
    // Route::post('/products/show-on-home',[ProductController::class,'showOnHome']);

    Route::get('/services', [ServiceController::class,'lists']);
    Route::get('/service/add', [ServiceController::class,'add']);
    Route::get('/service/trash', [ServiceController::class,'trashed']);
    Route::post('/service/store', [ServiceController::class,'store']);
    Route::get('/service/edit/{id}', [ServiceController::class,'edit']);
    Route::get('/service/trash/{id}', [ServiceController::class,'trash']);
    Route::get('/service/restore/{id}', [ServiceController::class,'restore']);
    Route::get('/service/delete/{id}', [ServiceController::class,'delete']);
    Route::post('/service/update', [ServiceController::class,'update']);
    Route::post('/service/show-on-home',[ServiceController::class,'showOnHome']);


    // Route::get('/quotations', [QuotationsController::class,'index']);
    // Route::get('/quotations/confirmation/{id}',[QuotationsController::class,'confirmation']);
    // Route::get('/quotations/seen/{id}',[QuotationsController::class,'seenMessage']);
    // Route::get('/quotations/unseen/{id}',[QuotationsController::class,'unseenMessage']);
    // Route::get('/quotations/delete/{id}',[QuotationsController::class,'deleteMessage']);
    // Route::get('/quotations/trashed',[QuotationsController::class,'trashedMessage']);
    // Route::get('/quotations/restore/{id}',[QuotationsController::class,'restoreMessage']);
    // Route::get('/quotations/parmanent-delete/{id}',[QuotationsController::class,'parmanentDeleteMessage']);
    // Route::get('/quotations/trashed/restore',[QuotationsController::class,'restoreAllMessage']);
    // Route::get('/quotations/trashed/clear',[QuotationsController::class,'clearAllMessage']);
    // Route::post('/quotation/earned',[QuotationsController::class,'changeEarnings']);

    Route::get('/clicks', [QuotationsController::class,'clicks']);
    Route::get('/users', [UserController::class,'index']);
    Route::get('/users/delete/{id}',[UserController::class,'delete']);

    Route::get('/blogs', [BlogController::class,'lists']);
    Route::get('/blog/add', [BlogController::class,'add']);
    Route::get('/blog/trash', [BlogController::class,'trashed']);
    Route::post('/blog/store', [BlogController::class,'store']);
    Route::get('/blog/edit/{id}', [BlogController::class,'edit']);
    Route::get('/blog/trash/{id}', [BlogController::class,'trash']);
    Route::get('/blog/restore/{id}', [BlogController::class,'restore']);
    Route::get('/blog/delete/{id}', [BlogController::class,'delete']);
    Route::post('/blog/update', [BlogController::class,'update']);
    Route::post('/blog/show-on-home',[BlogController::class,'showOnHome']);


    Route::get('/delete/{id}', [PageController::class,'destroy']);
    Route::post('/settings', [PageController::class,'updateSettings'])->name('settings.update');
    Route::get('/settings', [PageController::class,'settings'])->name('settings');
    Route::resource('/pages', 'PageController');

    Route::get('/inbox',[AdminInboxController::class,'viewMessage']);
    Route::get('/inbox/read/{id}',[AdminInboxController::class,'readMessage']);
    Route::get('/inbox/unread/{id}',[AdminInboxController::class,'unreadMessage']);
    Route::get('/inbox/delete/{id}',[AdminInboxController::class,'deleteMessage']);
    Route::get('/inbox/trashed',[AdminInboxController::class,'trashedMessage']);
    Route::get('/inbox/restore/{id}',[AdminInboxController::class,'restoreMessage']);
    Route::get('/inbox/parmanent-delete/{id}',[AdminInboxController::class,'parmanentDeleteMessage']);
    Route::get('/inbox/trashed/restore',[AdminInboxController::class,'restoreAllMessage']);
    Route::get('/inbox/trashed/clear',[AdminInboxController::class,'clearAllMessage']);



// });