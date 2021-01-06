<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BankTransactionCategory
 * @package App\Models
 */
class BankTransactionCategory extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bankTransactionCategories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'bankTransactionId',
        'categoryId',
        'name',
    ];

    /**
     * The the site for this budget
     *
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'siteId');
    }

    /**
     * Get the related access token
     *
     * @return BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(BankTransaction::class, 'bankTransactionId');
    }

    /**
     * Get the related account
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'bankCategoryId');
    }
}
