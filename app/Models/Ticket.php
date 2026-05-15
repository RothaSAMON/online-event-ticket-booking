<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_item_id',
        'ticket_code',
        'qr_code',
        'pdf_path',
        'is_scanned',
        'scanned_at',
    ];

    protected function casts(): array
    {
        return [
            'is_scanned' => 'boolean',
            'scanned_at' => 'datetime',
        ];
    }

    public function bookingItem(): BelongsTo
    {
        return $this->belongsTo(BookingItem::class);
    }

    public function scans(): HasMany
    {
        return $this->hasMany(TicketScan::class);
    }
}
