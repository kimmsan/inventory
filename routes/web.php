<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFGeneratorController;
use App\Models\Invoice;

use function GuzzleHttp\Promise\all;

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

// demo landing page route
Route::group(['middleware' => ['is_verified', 'need_to_install']], function () {
    if(config('app.is_demo_mode')){
        Route::get('/', function () {
            return view('landing');
        });
    }
});



// display system info
Route::get('/system-info', function () {
    return phpinfo();
})->name('systemInfo');

// email pdf generator routes
Route::get('/invoice/pdf/{slug}', [PDFGeneratorController::class, 'generateInvoicePDF'])->name('email.invoice.pdf');
Route::get('/purchase/pdf/{slug}', [PDFGeneratorController::class, 'generatePurchasePDF'])->name('email.purchase.pdf');
Route::get('/quotation/pdf/{slug}', [PDFGeneratorController::class, 'generateQuotationPDF'])->name('email.quotation.pdf');