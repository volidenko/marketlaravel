<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Список всех профилей
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $profiles = auth()->user()->profiles()->paginate(4);
        return view('user.profile.index', compact('profiles'));
    }

    /**
     * Форма для создания профиля
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('user.profile.create');
    }

    /**
     * Сохраняет профиль в базу данных
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'user_id' => 'in:' . auth()->user()->id,
            'title' => 'required|max:255',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|max:255',
            'address' => 'required|max:255',
        ]);
        $profile = Profile::create($request->all());
        return redirect()
            ->route('user.profile.show', ['profile' => $profile->id])
            ->with('success', 'Новый профиль успешно создан');
    }

    /**
     * Инфо профиля
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile) {
        if ($profile->user_id !== auth()->user()->id) {
            abort(404);
        }
        return view('user.profile.show', compact('profile'));
    }

    /**
     * Форма для редактирования профиля
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile) {
        if ($profile->user_id !== auth()->user()->id) {
            abort(404);
        }
        return view('user.profile.edit', compact('profile'));
    }

    /**
     * Обновляет профиль в БД
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile) {
        $this->validate($request, [
            'user_id' => 'in:' . auth()->user()->id,
            'title' => 'required|max:255',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|max:255',
            'address' => 'required|max:255',
        ]);
        $profile->update($request->all());
        return redirect()
            ->route('user.profile.show', ['profile' => $profile->id])
            ->with('success', 'Профиль был успешно отредактирован');
    }

    /**
     * Удаляет профиль з БД
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile) {
        if ($profile->user_id !== auth()->user()->id) {
            abort(404);
        }
        $profile->delete();
        return redirect()
            ->route('user.profile.index')
            ->with('success', 'Профиль был успешно удален');
    }
}
