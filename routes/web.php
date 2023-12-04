<?php

use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\IndexController;
 use App\Http\Controllers\ContestController;
 use App\Http\Controllers\HomeController;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Website;
use Illuminate\Support\Facades\Artisan;
use Spatie\Browsershot\Browsershot;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// http://eflip.com/admin   – mailto:eflipadmin@akash.com, pw=TovaSima2015


// Route::get('/find', function(){
//    $websites=Website::where('image_url','')->get();

//    foreach($websites as $website){
//       $website->image_url=strtotime(date('Y-m-d h:m:s')).'_'.str_replace('.','_',preg_replace( "#^[^:/.]*[:/]+#i", "", str_replace('/','',$website->url))).'__image.jpg';
//       $website->update();
//    }

//    return $websites;
// });

Route::get('/check', function(){

   // $client = new GuzzleHttp\Client();
   // $res = $client->get('https://api.grabz.it/services/convert?key=ZjYxMDdiMTJiYTM0NDRkNjgyMzcyMTM0ODE5ODgxMzI=&format=jpg&url=https%3A%2F%2Fspacex.com%2F');
   // $res->getStatusCode(); // 200
   // return  $res->getBody(); // { "type": "User", ....

   // $image=file_get_contents('https://api.grabz.it/services/convert?key=ZjYxMDdiMTJiYTM0NDRkNjgyMzcyMTM0ODE5ODgxMzI=&format=jpg&url=https%3A%2F%2Fspacex.com%2F');
   // file_put_contents(public_path('screenshot.png'), $image);

   // return phpinfo();
   return $base64Data = Browsershot::url('https://marketrealist.com/')
      ->setOption('landscape', true)
      ->windowSize(1024, 768)
      ->setScreenshotType('jpeg')

      // ->setNodeBinary('/usr/bin/node')
      // ->setNpmBinary('/usr/bin/npm')

      ->setNodeBinary('/opt/cpanel/ea-nodejs16/bin/node')
      ->setNpmBinary('/opt/cpanel/ea-nodejs16/bin/npm')

      ->setChromePath("/usr/bin/chromium-browser")
      ->waitUntilNetworkIdle()
      ->base64Screenshot();

   // return Browsershot::url('https://www.propublica.org')
   //    // ->waitUntilNetworkIdle()
   //    ->setCustomTempPath('/tmp')
   //    ->setOption('landscape', true)
   //    ->windowSize(1024, 768)
   //    ->setScreenshotType('jpeg', 30)
   //    ->setNodeBinary('/opt/cpanel/ea-nodejs16/bin/node')
   //    ->setNpmBinary('/opt/cpanel/ea-nodejs16/bin/npm')
   //    ->setChromePath("/usr/bin/chromium-browser")
   //    ->save(public_path("bbc.jpg"));
   });

Route::get('/check/{tag}', function($tag){
   // return Website::where('url','LIKE','%'.$tag.'%')->first();
});


Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/new', [IndexController::class, 'new'])->name('new');
Route::get('/intro', [IndexController::class, 'intro'])->name('intro');
Route::get('/help', [IndexController::class, 'help'])->name('help');
Route::get('/contact', [IndexController::class, 'contact'])->name('contact');
Route::post('/contact', [IndexController::class, 'storeContact'])->name('contact.store');
Route::get('/browse/{category}/{subcategory}', [IndexController::class, 'index'])->name('browse');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/favourite/{id}', [HomeController::class, 'favourite'])->name('favourite');
Route::post('/contest/rate/{id}', [HomeController::class, 'rateContest'])->name('contest.rate');


Route::get('/contest', [ContestController::class, 'index'])->name('contest');
Route::get('/contest/{slug}', [ContestController::class, 'view'])->name('contest.view');
Route::get('/contests/rules', [ContestController::class, 'rules'])->name('contest.rules');
Route::get('/contests/essays', [ContestController::class, 'essays'])->name('contest.essays');
Route::get('/contest/essay/add', [HomeController::class, 'addEssay'])->name('contest.essay.add');
Route::post('/contest/essay/add', [HomeController::class, 'storeEssay'])->name('contest.essay.store');
Route::post('/contest/category/add', [HomeController::class, 'storeCategory'])->name('contest.category.store');