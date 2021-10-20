<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'content',
        'image',
    ];

    public function products()  // Связь «один ко многим» таб. `categories` с таб. `products`
    {
        return $this->hasMany(Product::class);
    }

    public function children() // Связь «один ко многим» таб. `categories` с таб. `categories`
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function descendants() // Связь «один ко многим» таб. `categories` с таб. `categories`, позволяет получить не только дочерние категории, но и дочерние-дочерние
    {
        return $this->hasMany(Category::class, 'parent_id')->with('descendants');
    }

    public static function roots() // список корневых категорий
    {
        return self::where('parent_id', 0)->with('children')->get();
    }

    public static function hierarchy() // Возвращает список всех категорий каталога в виде дерева
    {
        return self::where('parent_id', 0)->with('descendants')->get();
    }

    public function validParent($id) // Проверяет, что переданный идентификатор id может быть родителем этой категории; что категорию не пытаются поместить внутрь себя
    {
        $id = (integer)$id;
        $ids = $this->getAllChildren($this->id); // получаем идентификаторы всех потомков текущей категории
        $ids[] = $this->id;
        return ! in_array($id, $ids);
    }

    public static function getAllChildren($id)  // Возвращает всех потомков категории с идентификатором $id
    {
        $children = self::where('parent_id', $id)->with('children')->get(); // получаем прямых потомков категории с идентификатором $id
        $ids = [];
        foreach ($children as $child) {
            $ids[] = $child->id;
            if ($child->children->count()) // для каждого прямого потомка получаем его прямых потомков
            {
                $ids = array_merge($ids, self::getAllChildren($child->id));
            }
        }
        return $ids;
    }
}
