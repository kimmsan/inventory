<?php

namespace App\Http\Resources;

use App\Models\InvoiceProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceReturnProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $invoiceProduct = InvoiceProduct::where('invoice_id', $this->invoiceReturn->invoice->id)->where('product_id', $this->product_id)->first();
        $invoiceQty = $invoiceProduct->quantity;

        return [
            'id' => $this->id,
            'returnQty' => $this->quantity,
            'invoiceQty' => $invoiceQty,
            'salePrice' => $this->sale_price,
            'purchasePrice' => $this->purchase_price,
            'productID' => $this->product->id,
            'productSlug' => $this->product->slug,
            'productCode' => $this->product->code,
            'productName' => $this->product->name,
            'productModel' => $this->product->model,
            'inventoryCount' => $this->product->inventory_count,
            'avgPurchasePrice' => $this->product->purchase_price,
            'productUnit' => $this->product->productUnit->code,
            'taxType' => $this->product->tax_type,
            'taxRate' => $this->product->productTax->rate,
        ];
    }
}
