<?php

namespace App\Models;

use App\Services\UserService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

/**
 * Class Category
 *
 * @package App\Models
 *
 * @property mixed id
 * @property mixed parentId
 * @property mixed name
 * @property int|mixed siteId
 * @property array children
 */
class Category extends BaseModel
{
    /**
     * Enables soft delete functionality
     */
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['children'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'parentId',
        'name',
    ];

    /**
     * Runs on user model boot
     */
    public static function boot()
    {
        parent::boot();

        // Delete all children when this category is deleted
        self::deleted(function ($model) {
            $children = Category::withTrashed()->siteOnly()->where('parentId', $model->id)->get();
            foreach ($children as $child) {
                $child->delete();
            }
        });

        // Restore all children when this category is restored
        self::restored(function ($model) {
            $children = Category::withTrashed()->siteOnly()->where('parentId', $model->id)->get();
            foreach ($children as $child) {
                $child->restore();
            }
        });
    }

    /**
     * Get all Category children
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parentId');
    }

    /**
     * Get the category or all categories (for a site) and return as a nested array
     *
     * @param int|null $id The optional category id if only one category is desired
     * @param int|null $siteId The site id. If not given we will try to get it from the UserService::getSiteId() method
     *
     * @return array|null
     */
    public static function toNestedArray(?int $id = null, ?int $siteId = null): ?array
    {
        // There must be a siteId else all categories for all sites will be returned
        if (!$siteId = $siteId ?? UserService::getSiteId()) {
            return null;
        }

        $query = Category::where('siteId', $siteId)->whereNull('parentId');

        // Add id if the id we supplied
        if ($id !== null) {
            $query->where('id', $id);
        }

        $data = [];
        foreach ($query->get() as $category) {
            $data[] = self::recursiveArray($category);
        }

        return $data;
    }

    /**
     * Used with the toNestedArray to get a nested array of categories
     *
     * @param Category $category
     *
     * @return array
     */
    protected static function recursiveArray(Category $category): array
    {
        $data = [
            'id' => $category->id,
            'parentId' => $category->parentId,
            'name' => $category->name,
            'children' => [],
        ];

        foreach ($category->children as $child) {
            $data['children'] = self::recursiveArray($child);
        }

        return $data;
    }
}
