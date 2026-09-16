<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRefundAccount extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'label',
        'upi_id',
        'bank_name',
        'account_number',
        'ifsc_code',
        'account_holder',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMaskedAccountAttribute(): string
    {
        if ($this->type === 'upi') {
            return $this->upi_id ?: 'UPI';
        }

        return $this->bank_name
            ? $this->bank_name . ' ****' . substr((string) $this->account_number, -4)
            : 'Bank Account';
    }
}
