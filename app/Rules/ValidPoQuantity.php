<?php

namespace App\Rules;

use App\Models\OrderDetail;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPoQuantity implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        preg_match('/rows\.(\d+)\.quantity/', $attribute, $matches);
        $index = $matches[1] ?? null;

        if ($index !== null) {
            $orderDetailId = request()->input("rows.$index.order_detail_line_id");

            if ($orderDetailId) {
                $orderDetail = OrderDetail::find($orderDetailId);
                if ($orderDetail) {
                    $availableQty = $orderDetail->quantity - $orderDetail->delivered_qty;
                    if ($value > $availableQty) {
                        $fail("The entered quantity ($value) exceeds the available quantity ($availableQty).");
                    }
                }
            }
        }

    }
    
}
