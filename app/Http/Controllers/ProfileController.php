<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Monarobase\CountryList\CountryListFacade;

class ProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->first();
        $friendsId1 = Friend::where('user_id', $user->id)
            ->get()->pluck('friend_id');
        $friendsId2 = Friend::where([['status', true], ['friend_id', $user->id]])
            ->get()->pluck('user_id');
        $friendsId = $friendsId1->merge($friendsId2);
        $friendRequestsId = Friend::where([['status', false], ['friend_id', $user->id]])
            ->get()->pluck('user_id');

        if (($key = array_search($user->id, $friendsId->toArray())) !== false) {
            unset($friendsId[$key]);
        }
        if (($key = array_search($user->id, $friendRequestsId->toArray())) !== false) {
            unset($friendsId[$key]);
        }

        $friends = User::whereIn('id', $friendsId)->latest()->get()->toArray();
        $friendRequests = User::whereIn('id', $friendRequestsId)->latest()->get()->toArray();

        //dd($user->toArray());
        return Inertia::render('Profile/Index', [
            'user' => $user,
            'feeds' => Post::where('user_id', $user->id)->latest()->paginate(5),
            'currentUser' => \request()->user()->id === $user->id,
            'friends' => $friends,
            'friendRequests' => $friendRequests,
        ]);
    }

    public function edit()
    {
        return Inertia::render('Profile/Edit', [
            'user' => \request()->user(),
            'countries' => CountryListFacade::getList('en', 'php')
        ]);
    }

    public function update()
    {
        $randKey = Carbon::now()->format('_dnygis');
        $path = null;
        if (request()->file('avatar')) {
            $ext = '.' . request()->file('avatar')->getClientOriginalExtension();
            request()->file('avatar')->storeAs('public/avatars', request()->user()->id . $randKey . $ext);
            $path = '/storage/avatars/' . request()->user()->id . $randKey . $ext;
        } else {
            $path = \request('avatar');
        }

        \request()->user()->update([
            'name' => \request('name'),
            'bio' => \request('bio'),
            'website' => \request('website'),
            'country' => \request('country'),
            'city' => \request('city'),
            'avatar' => $path,
        ]);
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
