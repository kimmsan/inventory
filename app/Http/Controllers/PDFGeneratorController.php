<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFGeneratorController extends Controller
{
    // generate invoice pdf
    public function generateInvoicePDF($slug){
        $invoice = Invoice::where('slug', $slug)->with('client', 'invoiceProducts.invoice', 'invoicePayments.invoicePaymentTransaction.cashbookAccount', 'invoiceProducts.product.productUnit', 'invoiceProducts.product.productTax', 'invoiceTax', 'user')->first();
        // generate and save pdf
        $pdf = Pdf::loadHTML(view('pdf.invoice-template', [
            'invoice' => $invoice
        ]));
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        return $pdf->stream('Invoice-'.$invoice->slug.'.pdf');
    }

    // generate purchase pdf
    public function generatePurchasePDF($slug){
        $purchase = Purchase::with('supplier', 'purchaseProducts.purchase', 'purchaseReturn', 'purchasePayments.purchasePaymentTransaction.cashbookAccount', 'purchaseProducts.product.productUnit', 'purchaseProducts.product.productTax',  'user')->where('slug', $slug)->first();
        // generate and save pdf
        $pdf = Pdf::loadHTML(view('pdf.purchase-template', [
            'purchase' => $purchase
        ]));
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        return $pdf->stream('Purchase-'.$purchase->slug.'.pdf');
    }

    // generate purchase pdf
    public function generateQuotationPDF($slug){
        $quotation = Quotation::with('client', 'quotationProducts.product.productUnit', 'quotationProducts.product.productTax', 'user')->where('slug', $slug)->firstOrFail();
        // generate and save pdf
        $pdf = Pdf::loadHTML(view('pdf.quotation-template', [
            'quotation' => $quotation
        ]));
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        return $pdf->stream('Quotation--'.$quotation->slug.'.pdf');
    }
}