<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
	protected $fillable = [
		'name',
		'sku',
		'category',
		'supplier_id',
		'description',
		'price',
		'stock',
		'minimum_stock',
		'image',
		'is_active',
	];

	protected $casts = [
		'price' => 'decimal:2',
		'stock' => 'integer',
		'minimum_stock' => 'integer',
		'is_active' => 'boolean',
	];

	public function supplier(): BelongsTo
	{
		return $this->belongsTo(Supplier::class);
	}

	public function stockMovements(): HasMany
	{
		return $this->hasMany(StockMovement::class);
	}
}
