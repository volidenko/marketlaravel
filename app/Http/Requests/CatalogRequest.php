<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CategoryParent;

abstract class CatalogRequest extends FormRequest {
    /**
     * Cущность, с которой работаем: категория, бренд, товар
     * @var array
     */
    protected $entity = [];

    public function authorize() {
        return true;
    }

    public function rules() {
        switch ($this->method()) {
            case 'POST':
                return $this->createItem();
            case 'PUT':
            case 'PATCH':
                return $this->updateItem();
        }
    }

    // Дефолтные правила для проверки данных при добавлении категории, бренда или товара
    protected function createItem() {
        return [
            'name' => [
                'required',
                'max:100',
            ],
            'slug' => [
                'required',
                'max:100',
                'unique:'.$this->entity['table'].',slug',
                'regex:~^[-_a-z0-9]+$~i',
            ],
            'image' => [
                'mimes:jpeg,jpg,png',
                'max:5000'
            ],
        ];
    }

    // Дефолтные правила для проверки данных при обновлении категории, бренда или товара
    protected function updateItem() {
        // объект модели из маршрута: admin/entity/{entity}
        $model = $this->route($this->entity['name']);
        return [
            'name' => [
                'required',
                'max:100',
            ],
            'slug' => [
                'required',
                'max:100',
                // проверка на уникальность slug, исключая эту сущность по идентифкатору
                'unique:'.$this->entity['table'].',slug,'.$model->id.',id',
                'regex:~^[-_a-z0-9]+$~i',
            ],
            'image' => [
                'mimes:jpeg,jpg,png',
                'max:5000'
            ],
        ];
    }
}
