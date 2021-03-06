<?php

namespace Spatie\WelcomeNotification;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController
{
    use RedirectsUsers;

    public function showWelcomeForm(Request $request, User $user)
    {
        return view('welcomeNotification::welcome')->with([
            'email' => $request->email,
            'user' => $user,
        ]);
    }

    public function savePassword(Request $request, User $user)
    {
        $request->validate($this->rules());

        $user->password = bcrypt($request->password);
        $user->welcome_valid_until = null;
        $user->save();

        auth()->login($user);

        return $this->sendPasswordSavedResponse();
    }

    protected function sendPasswordSavedResponse(): Response
    {
        return redirect()->to($this->redirectPath())->with('status', 'Welcome! You are now logged in!');
    }

    protected function rules()
    {
        return [
            'password' => 'required|confirmed|min:6',
        ];
    }
}
