<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\HashNameAlreadyAssignedException;
use App\Exceptions\URLNotWhitelistedException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ShortURL\ShortURLController;
use App\Http\Controllers\UserController;
use App\Models\ShortURL\ShortURL;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function urls()
    {
        return view('admin.shorturl.index')->with('urls', ShortURL::all());
    }

    public function editURL(int $id)
    {
        $url = ShortURL::find($id);
        return view('admin.shorturl.edit')->with('url', $url)->with('users', User::all());
    }

    public function deleteURL(int $id)
    {
        $urlController = resolve(ShortURLController::class);
        $urlController->delete($id);
        return redirect('/admin/urls');
    }

    public function patchURL(Request $request, int $id)
    {
        $urlController = resolve(ShortURLController::class);

        try {
            $urlController->update($request, $id);
        } catch (URLNotWhitelistedException | HashNameAlreadyAssignedException $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect('/admin/urls');
    }

    public function users()
    {
        return view('admin.users.index')->with('users', User::all());
    }

    public function editUser(int $id)
    {
        $user = User::find($id);
        return view('admin.users.edit')->with('user', $user);
    }

    public function deleteUser(int $id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('/admin/users');
    }

    public function patchUser(Request $request, int $id)
    {
        $user = User::find($id);
        $this->validate($request, [
            'name' => 'present',
            'requests_per_minute' => 'required|integer',
            'api_token' => 'required|max:60|min:60|alpha_num',
            'email' => 'required|min:3|email',
            'list' => 'nullable|alpha',
            'notes' => 'nullable',
            'password' => 'present'
        ]);

        $user->name = $request->name;
        $user->requests_per_minute = $request->requests_per_minute;
        $user->email = $request->email;
        $user->api_token = $request->api_token;
        $user->notes = $request->notes;

        if ($request->has('list')) {
            if ($request->list === 'blacklisted') {
                $user->blacklisted = true;
                $user->whitelisted = false;
            }

            if ($request->list === 'whitelisted') {
                $user->whitelisted = true;
                $user->blacklisted = false;
            }

            if ($request->list === 'nooptions') {
                $user->whitelisted = false;
                $user->blacklisted = false;
            }
        }

        if (!is_null($request->password)) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect('/admin/users');
    }

    public function routes()
    {
        return view('admin.routes.index');
    }
}
