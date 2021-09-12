<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageSaver;
use App\Http\Requests\CategoryCatalogRequest;

class CategoryController extends Controller
{
    private $imageSaver;

    public function __construct(ImageSaver $imageSaver) {
        $this->imageSaver = $imageSaver;
    }
    /**
     * Показывает список всех категорий
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function index() {
        $roots = Category::roots();
        return view('admin.category.index', compact('roots'));
    }

    /**
     * Показывает форму для создания категории
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $parents = Category::roots();
        return view('admin.category.create', compact('parents'));
    }

    /**
     * Сохраняет новую категорию в базу данных
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryCatalogRequest $request) {
        $data = $request->all();
        $data['image'] = $this->imageSaver->upload($request, null, 'category');
        $category = Category::create($data);
        return redirect()
            ->route('admin.category.show', ['category' => $category->id])
            ->with('success', 'Новая категория успешно создана');
    }

    /**
     * Показывает страницу категории
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category) {
        return view('admin.category.show', compact('category'));
    }

    /**
     * Показывает форму для редактирования категории
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category) {
        $parents = Category::roots();
        return view('admin.category.edit', compact('category', 'parents'));
    }

    /**
     * Обновляет категорию каталога
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryCatalogRequest $request, Category $category) {
        $data = $request->all();
        $data['image'] = $this->imageSaver->upload($request, $category, 'category');
        $category->update($data);
        return redirect()
            ->route('admin.category.show', ['category' => $category->id])
            ->with('success', 'Категория была успешно исправлена');

        // if ($request->remove) { // если надо удалить изображение
        //     $old = $category->image;
        //     if ($old) {
        //         Storage::disk('public')->delete('catalog/category/source/' . $old);
        //     }
        // }
        // $file = $request->file('image');
        // if ($file) { // был загружен файл изображения
        //     $path = $file->store('catalog/category/source', 'public');
        //     $base = basename($path);
        //     // удаляем старый файл изображения
        //     $old = $category->image;
        //     if ($old) {
        //         Storage::disk('public')->delete('catalog/category/source/' . $old);
        //     }
        // }
        // $data = $request->all();
        // $data['image'] = $base ?? null;
        // $category->update($data);
        // return redirect()
        //     ->route('admin.category.show', ['category' => $category->id])
        //     ->with('success', 'Категория была успешно исправлена');
    }

    /**
     * Удаление категории каталога
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category) {
        if ($category->children->count()) {
            $errors[] = 'Нельзя удалить категорию с дочерними категориями';
        }
        if ($category->products->count()) {
            $errors[] = 'Нельзя удалить категорию, которая содержит товары';
        }
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }
        $this->imageSaver->remove($category, 'category');
        $category->delete();
        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Категория каталога успешно удалена');
    }
}
