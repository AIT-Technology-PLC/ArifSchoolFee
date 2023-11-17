<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PadField extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_master_field' => 'boolean',
        'is_required' => 'boolean',
        'is_visible' => 'boolean',
        'is_printable' => 'boolean',
        'is_readonly' => 'boolean',
    ];

    protected $cascadeDeletes = [
        'padRelation',
    ];

    public function pad()
    {
        return $this->belongsTo(Pad::class);
    }

    public function padRelation()
    {
        return $this->hasOne(PadRelation::class);
    }

    public function scopeMasterFields($query)
    {
        return $query->where('is_master_field', 1);
    }

    public function scopeDetailFields($query)
    {
        return $query->where('is_master_field', 0);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', 1);
    }

    public function scopePrintable($query)
    {
        return $query->where('is_printable', 1);
    }

    public function scopeInputTypeFile($query)
    {
        return $query->where('tag_type', 'file');
    }

    public function scopeExcludeReservedLabels($query, $pad)
    {
        return $query->whereNotIn('label', static::reservedLabels($pad));
    }

    public function isMasterField()
    {
        return $this->is_master_field;
    }

    public function isRequired()
    {
        return $this->is_required;
    }

    public function isVisible()
    {
        return $this->is_visible;
    }

    public function isPrintable()
    {
        return $this->is_printable;
    }

    public function isReadonly()
    {
        return $this->is_readonly;
    }

    public function isTagInput()
    {
        return str($this->tag)->lower() == 'input';
    }

    public function isTagSelect()
    {
        return str($this->tag)->lower() == 'select';
    }

    public function isTagTextarea()
    {
        return str($this->tag)->lower() == 'textarea';
    }

    public function isInputTypeCheckbox()
    {
        return str($this->tag_type)->lower() == 'checkbox';
    }

    public function isInputTypeRadio()
    {
        return $this->isTagInput() && str($this->tag_type)->lower() == 'radio';
    }

    public function hasRelation()
    {
        return $this->padRelation ? true : false;
    }

    public function isInputTypeFile()
    {
        return str($this->tag_type)->lower() == 'file';
    }

    public function isUnitPrice()
    {
        return $this->label == 'Unit Price';
    }

    public function isQuantity()
    {
        return $this->label == 'Quantity';
    }

    public function areBatchingFields()
    {
        return $this->label == 'Batch No' || $this->label == 'Expires On';
    }

    public function isBatchNoField()
    {
        return $this->label == 'Batch No';
    }

    public function isMerchandiseBatchField()
    {
        return $this->label == 'Batch' && $this->padRelation()->exists();
    }

    public static function reservedLabels($inventoryOperationType = null, $hasPrices = null, $pad = null)
    {
        if (!is_null($pad)) {
            $inventoryOperationType = $pad->inventory_operation_type;
            $hasPrices = $pad->has_prices;
        }

        $labels = collect([
            'Unit Price',
            'Payment Method',
            'Cash Received',
            'Credit Due Date',
            'Batch No',
            'Expires On',
            'Batch',
            'Supplier',
            'Customer',
            'User',
            'Warehouse',
            'Product',
            'Contact',
        ]);

        if ($inventoryOperationType != 'none') {
            $labels->push('Quantity', 'To', 'From');
        }

        if ($hasPrices) {
            $labels->push('Quantity');
        }

        return $labels->toArray();
    }
}
