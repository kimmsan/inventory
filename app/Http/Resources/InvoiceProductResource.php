<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class InvoiceProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $returnQty = DB::table('invoice_return_products')
            ->join('products', 'products.id', '=', 'invoice_return_products.product_id')
            ->join('invoice_returns', 'invoice_returns.id', '=', 'invoice_return_products.return_id')
            ->join('invoices', 'invoices.id', '=', 'invoice_returns.invoice_id')
            ->where('invoice_return_products.product_id', '=', $this->product_id)
            ->where('invoice_returns.invoice_id', '=', $this->invoice->id)
            ->sum('invoice_return_products.quantity');

        return [
            'id' => $this->id,
            'purchasePrice' => $this->purchase_price,
            'salePrice' => $this->sale_price,
            'quantity' => $this->quantity,
            'unitCost' => $this->unit_cost,
            'unitTax' => $this->tax_amount,
            'total' => $this->quantity * $this->sale_price,
            'returnQty' => $returnQty > 0 ? $returnQty : 0,
            'purchasePricetotal' => $this->quantity * $this->purchase_price,
            'unitCostTotal' => $this->quantity * $this->unit_cost,
            'taxTotal' => $this->quantity * $this->tax_amount,
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
