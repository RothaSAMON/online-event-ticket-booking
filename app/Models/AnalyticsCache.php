<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsCache extends Model
{
    use HasFactory;

    protected $table = 'analytics_cache';

    protected $fillable = [
        'event_id',
        'total_sales',
        'total_tickets_sold',
        'total_attendees',
    ];

    protected function casts(): array
    {
        return [
            'total_sales'        => 'decimal:2',
            'total_tickets_sold' => 'integer',
            'total_attendees'    => 'integer',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
