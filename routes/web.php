<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tweet;
use App\Models\Follower;
use App\Models\Like;

// Sucks that can't simply use 'redirect' because of HTMX.
// Htmx doesn't reload/redirect if status code isn't 2xx.
// @TODO: Maybe I don't have to do this? https://htmx.org/docs/#requests
function hx_redirect(string $path = "/") {
  return response('redirect')->header("HX-Redirect", $path);
}

Route::get("/", function () {
  if (Auth::check()) { return redirect("/home"); }
  else { return redirect("/login"); }
});

Route::get('/home', function () {
  return view('home', [
    "user_id" => Auth::user()->id,
    "tweets" => Tweet::all()]
  );
})->middleware('auth');

Route::get('/register', function () {
  if (Auth::check()) { return redirect("/home"); }
  return view('register');
})->name("register");

Route::post('/register', function (Request $request) {
  if (User::where('email', $request->input("email"))->value("email")) {
    return response("User already exists with this email", 400);
  }

  $name = $request->input("name");
  if (User::where('name', $name)->value("name")) {
    return response("User with '$name' already exists", 400);
  }

  $info = $request->validate([
    "email" => "required|email|unique:users,email",
    "password" => "required|string",
    "name" => "required|string|unique:users,name",
    "nickname" => "required|string",
  ]);

  $user = User::create($info);
  Auth::login($user, true);
  return hx_redirect("/home");
});

Route::get('/login', function () {
  if (Auth::check()) { return redirect("/home"); }
  return view('login');
})->name("login");

Route::post('/login', function (Request $request) {
  $info = $request->validate([
    "email" => "required|email",
    "password" => "required|string",
  ]);

  if (Auth::attempt($info, true)) {
    $request->session()->regenerate();
    return hx_redirect("/home");
  }

  return response("User not found", 400);
});

Route::get("/logout", function (Request $request) {
  Auth::logout();
  $request->session()->invalidate();
  $request->session()->regenerateToken();
  return hx_redirect('/login');
})->middleware('auth');

Route::middleware("auth")->group(function () {
  Route::post("/tweet", function (Request $request) {
    $tweet = $request->validate([
      "content" => "required|string",
      "tweeted_by" => "required|integer",
    ]);

    Tweet::create($tweet); // @TODO @Temporary: Send html response for the newly created tweet
    return response("Tweeted Successfully", 204);
  });

  Route::post("/edit-profile", function (Request $request) {
    $info = $request->validate([
      "background_picture" => "nullable|image|max:10240", // Max 10MB
      "profile_picture" => "nullable|image|max:2048", // Max 2MB
      "nickname" => "required|string",
      "bio" => "nullable|string",
    ]);

    if (array_key_exists("profile_picture", $info)) {
      $info['profile_picture'] = "/storage/".$request->file("profile_picture")->store("uploads", "public") or function() {
        return response("File operation failed!", 500);
      };
    }
    if (array_key_exists("background_picture", $info)) {
      $info['background_picture'] = "/storage/".$request->file("background_picture")->store("uploads", "public") or function() {
        return response("File operation failed!", 500);
      };
    }

    User::where("id", Auth::user()->id)->update($info);
    return response("Update successfully", 200);
  });


  Route::post("/unfollow/{follower_id}", function (Request $request, int $follower_id) {
    $user = Follower::where("follower_id", $follower_id);
    if (!$user) {
      return response("User doesn't exists", 403);
    }

    $user->delete();
    return response("Follow");
  });

  Route::post("/follow/{user_id}", function (Request $request, int $user_id) {
    if (!User::find($user_id)) {
      return response("User doesn't exists", 403);
    }

    $info = $request->validate([
      "user_id" => "required|integer",
      "follower_id" => "required|integer",
    ]);

    Follower::create($info);
    return response("Following");
  });

  Route::post("/like/{tweet_id}", function (int $tweet_id) {
    $tweet = Tweet::find($tweet_id);
    if (!$tweet) { return response("Bad Request", 403); }

    $record = DB::selectOne("select * from likes where user_id = ? and tweet_id = ?", [Auth::user()->id, $tweet_id]);
    if ($record) { // Tweek is already liked
      Like::where('tweet_id', $tweet_id)
        ->where('user_id', Auth::user()->id)
        ->delete();
      return response("unliked tweet", 204);
    }

    $like_tweet = new Like;
    $like_tweet->user_id = Auth::user()->id;
    $like_tweet->tweet_id = $tweet_id;
    $like_tweet->save();
    return response("Tweet Liked!", 204);
  });
});

Route::get("/{user}", function (string $user) {
  $user = User::firstWhere("name", $user);
  if (!$user) { // @Temporary: display message user not found
    return redirect("/home");
  };

  return view("profile", [
    "user" => $user,
    "auth_user" => Auth::user(),
    "followers" => Follower::where("user_id", $user->id)->count(),
    "following" => Follower::where("follower_id", $user->id)->count(),
  ]);
})->middleware('auth');

