<?php

namespace App\Rules;

use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class ValidateChessisTracker implements Rule
{
    private $details;

    private $message;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function passes($attribute, $value)
    {
        if (!userCompany()->allowChassisTracker()) {
            return true;
        }

        if (userCompany()->allowChassisTracker()) {
            foreach ($this->details as $detail) {
                if (isset($detail['quantity']) && (Product::find($detail['product_id']))->has_chassis_tracker) {
                    if (count($detail['chassisTracker']) > $detail['quantity']) {
                        $this->message = 'Total Chassis Numbers should not be greater than Quantity';

                        return false;
                    }

                    return true;
                }

                if (isset($detail['available']) && (Product::find($detail['product_id']))->has_chassis_tracker) {
                    if (count($detail['chassisTracker']) > $detail['available']) {
                        $this->message = 'Total Chassis Numbers should not be greater than Available';

                        return false;
                    }

                    return true;
                }
            }

            return true;
        }
    }

    public function message()
    {
        return $this->message;
    }
}
