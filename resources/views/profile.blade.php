@use('App\Models\Follower')
@use('App\Models\Tweet')
@use('Carbon\Carbon')

<x-layout>
  @if ($auth_user->id === $user->id)
  <dialog class="border w-full max-w-[40rem] border-zinc-400 mt-5 md:mt-20 bg-black text-white mx-auto rounded-md" id="dialog">
    <form class="px-2" enctype="multipart/form-data" hx-post="/edit-profile">
      @csrf
      <div class="font-bold py-2 sticky z-2 bg-black top-0 flex justify-between items-center text-lg"r>
        <p><span onclick="document.getElementById('dialog').close()" class="mr-3 cursor-pointer">❌</span>Edit Profile</p>
        <button onclick="save()" class="bg-white text-black focus:outline-none px-2 py-1 rounded-lg" type="submit">Save</button>
      </div>
      <script>
      function save() {
        document.getElementById("dialog").close();
        setTimeout(() => window.location.reload(), 400);
      }
      </script>

      <div class="col-span-full border-b border-white/25 pb-4 my-4">
        <label for="cover-photo" class="block text-sm/6 font-medium text-white">Cover photo</label>

        @if ($user->background_picture === "none")
        <div id="bg-img-parent" class="mt-2 flex justify-center rounded-lg border border-dashed border-white/25 px-6 py-10">
          <div id="bg-img-container" class="text-center">
            <svg viewBox="0 0 24 24" fill="currentColor" data-slot="icon" aria-hidden="true" class="mx-auto size-12 text-gray-600">
              <path d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" fill-rule="evenodd" />
            </svg>
            <div class="mt-4 flex text-sm/6 text-gray-400">
              <label for="background_picture" class="relative cursor-pointer rounded-md bg-transparent font-semibold text-indigo-400 focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-indigo-500 hover:text-indigo-300">
                <span>Upload a file</span>
                <input onchange="update_background_image(event)" id="background_picture"
                  type="file" name="background_picture" class="sr-only" accept="image/*" />
              </label>
              <p class="pl-1">or drag and drop</p>
            </div>
            <p class="text-xs/5 text-gray-400">PNG, JPG, GIF up to 10MB</p>
          </div>
        </div>

        <script>
        function update_background_image(event) {
          const img = event.target.files[0];
          const img_path = URL.createObjectURL(img);
          document.getElementById("bg-img-container").hidden = true;
          const parent = document.getElementById("bg-img-parent");
          const img_el = document.createElement("img");
          img_el.src = img_path;
          img_el.classList.add("h-[15rem]", "object-center", "object-cover", "w-full");

          parent.classList.remove("px-6", "py-10");
          parent.appendChild(img_el);
        }
        </script>
        @else
        <div class="mt-2 relative">
          <img id="display_image" class="rounded-lg border border-dashed border-white/25 w-full h-[15rem]
            object-center object-cover" src="{{$user->background_picture}}" />
          <input onchange="update_background_image(event)" id="background_picture" name="background_picture" class="sr-only" type="file" accept="image/*" />
          <label style="transform: translate(-50%, -50%);" class="cursor-pointer absolute top-[50%] left-[50%]" for="background_picture">
            <svg class="w-[3rem] fill-white/40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M320 576C461.4 576 576 461.4 576 320C576 178.6 461.4 64 320 64C178.6 64 64 178.6 64 320C64 461.4 178.6 576 320 576zM337 199L417 279C426.4 288.4 426.4 303.6 417 312.9C407.6 322.2 392.4 322.3 383.1 312.9L344.1 273.9L344.1 424C344.1 437.3 333.4 448 320.1 448C306.8 448 296.1 437.3 296.1 424L296.1 273.9L257.1 312.9C247.7 322.3 232.5 322.3 223.2 312.9C213.9 303.5 213.8 288.3 223.2 279L303.2 199C312.6 189.6 327.8 189.6 337.1 199z"/></svg>
          </label>
        </div>

        <script>
        function update_background_image(event) {
          const img = event.target.files[0];
          const img_path = URL.createObjectURL(img);
          document.getElementById("display_image").src = img_path;
        }
        </script>
        @endif
      </div>

      <div class="my-4 col-span-full border-b border-white/25 pb-4">
        <label for="photo" class="block text-sm/6 font-medium text-white">Photo</label>
        <div id="profile-container" class="mt-2 flex items-center gap-x-3">
          @if ($user->profile_picture === "/default-profile-picture.svg")
          <svg id="default-picture" viewBox="0 0 24 24" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-20 text-gray-500">
            <path d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" fill-rule="evenodd" />
          </svg>
          @else
          <img id="profile_image" class="size-20 object-cover rounded-full" src="{{$user->profile_picture}}" />
          @endif
          <input onchange="update_profile_picture(event)" id="profile_picture"
            name="profile_picture" class="sr-only" type="file" accept="image/*" />
          <label for="profile_picture" type="button"
            class="rounded-md cursor-pointer bg-white/10 px-3 py-2 text-sm font-semibold
            text-white inset-ring inset-ring-white/5 hover:bg-white/20"
          >Change</label>
        </div>

        <script>
        function update_profile_picture(event) {
          const img = event.target.files[0];
          const img_path = URL.createObjectURL(img);
          if (document.getElementById("default-picture")) {
            document.getElementById("default-picture").remove();
          } else {
            document.getElementById("profile_image").remove();
          }
          const img_el = document.createElement("img");
          img_el.src = img_path;
          img_el.classList.add("size-20", "rounded-full", "object-cover");
          img_el.id = "profile_image";
          const container = document.getElementById("profile-container");
          container.prepend(img_el);
        }
        </script>
      </div>

      <div class="sm:col-span-4 border-b border-white/25 pb-4">
        <label for="username" class="block text-sm/6 font-medium text-white">Name</label>
        <div class="mt-2">
          <div class="flex items-center rounded-md bg-white/5 pl-3 outline-1 -outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
            <input required id="username" type="text" name="nickname" placeholder="janesmith"
              class="block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base
              text-white placeholder:text-gray-500 focus:outline-none sm:text-sm/6"
              value="{{$user->nickname}}" />
          </div>
        </div>
      </div>

      <div class="col-span-full my-4">
        <label for="bio" class="block text-sm/6 font-medium text-white">Bio</label>
        <div class="mt-2">
          <textarea placeholder="Write a few sentences about yourself." id="bio"
            name="bio" rows="3" class="block w-full rounded-md bg-white/5 px-3 py-1.5
            text-base text-white outline-1 -outline-offset-1 outline-white/10
            placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2
            focus:outline-indigo-500 sm:text-sm/6">{{$user->bio}}</textarea>
        </div>
      </div>
    </form>
  </dialog>
  @endif

  <main class="max-w-[70rem] px-2 my-5 mx-auto">
    <div class="flex">
      <x-side-navbar :name="$auth_user->name"/>

      <div class="w-full">
        <img
          loading="lazy"
          class="h-[10rem] relative object-center object-cover w-full border
          border-white/25 rounded-sm"
          @if ($user->background_picture === "none")
          src="https://picsum.photos/800/300"
          @else
          src="{{$user->background_picture}}"
          @endif
        />

        <img class="w-[6rem] h-[6rem] object-cover absolute ml-2 top-[8rem] rounded-full bg-white" src="{{$user->profile_picture}}" />

        @if ($auth_user->id === $user->id)
        <div class="flex items-center mt-4 gap-2">
          <button
            onclick="document.getElementById('dialog').showModal()"
            class="border cursor-pointer hover:bg-zinc-800 block ml-auto border-white
            px-4 py-2 font-semibold text-lg rounded-lg">Edit profile</button>
          <button
            hx-get="/logout"
            class="border cursor-pointer hover:bg-zinc-800 block border-white
            px-4 py-2 font-semibold text-lg rounded-lg hover:border-red-400 hover:text-red-400">Logout</button>
        </div>
        @elseif (Follower::where("user_id", $user->id)->first())
        <form>
          @csrf
          <input name="user_id" type="hidden" value="{{$user->id}}" />
          <input name="follower_id" type="hidden" value="{{$auth_user->id}}" />
          <button
            type="submit"
            hx-post="/unfollow/{{$auth_user->id}}"
            hx-target="this"
            hx-swap="innerHTML"
            id="following"
            class="border cursor-pointer hover:bg-zinc-800 block ml-auto mt-4 border-white
            px-4 py-2 font-semibold text-lg rounded-lg">Following</button>
          <script>
          const btn = document.getElementById("following");
          const unfollow_btn_style = ["hover:text-red-500", "hover:border-red-500"];
          btn.addEventListener("mouseover",  () => {
            if (btn.innerText === "Following") {
              btn.classList.add(...unfollow_btn_style);
              btn.innerText = "Unfollow";
            }
          });

          btn.addEventListener("mouseleave", () => {
            if (btn.innerText === "Follow") {
              btn.classList.remove(...unfollow_btn_style);
            } else { btn.innerText = "Following"; }
          });
          </script>
        </form>
          @else
          <form>
            @csrf
            <input name="user_id" type="hidden" value="{{$user->id}}" />
            <input name="follower_id" type="hidden" value="{{$auth_user->id}}" />
            <button
              type="submit"
              hx-post="/follow/{{$user->id}}"
              hx-target="this"
              hx-swap="innerHTML"
              class="border cursor-pointer hover:bg-zinc-800 block ml-auto mt-4 border-white
              px-4 py-2 font-semibold text-lg rounded-lg">Follow</button>
          </form>
          @endif

          <h1 class="text-2xl font-bold">{{$user->nickname}}</h1>
          <p class="text-zinc-500"><span>@</span>{{$user->name}}</p>
          <p class="mt-4 text-sm w-full max-w-[30rem]">{{$user->bio}}</p>
          <div class="flex gap-5 my-2">
            <p class="text-zinc-500"><span class="font-bold text-white">{{$following}}</span> Following</p>
            <p class="text-zinc-500"><span class="font-bold text-white">{{$followers}}</span> Followers</p>
          </div>

          <nav class="mt-5" >
            <button class="font-bold">Posts</button>

            <div id="tweets" class="mt-2 border-t border-white">
              @php $tweets = Tweet::where("tweeted_by", $user->id)->get(); @endphp
              @foreach ($tweets as $tweet)
              <div class="py-5 border-b border-white border-zinc-500">
                @php
                $date = Carbon::parse($tweet->created_at);
                $likes = count(DB::select("select * from likes where tweet_id = ?", [$tweet->id]));

                $is_liked = false;
                if (DB::selectOne("select * from likes where user_id = ? and tweet_id = ?",
                [Auth::user()->id, $tweet->id])) { $is_liked = true; }
                @endphp

                <div class="flex gap-2">
                  <img class="w-[3rem] h-[3rem] object-cover self-start rounded-full"
                    src="{{$user->profile_picture}}" />
                  <div class="w-full max-w-[40rem]">
                    <p class="font-bold">
                      <a class="hover:underline" href="/{{$user->name}}">{{$user->nickname}}</a>
                      <span class="ml-1 text-zinc-500 font-normal
                        text-sm"><span>@</span>{{$user->name}} ·</span>
                      <span class="text-zinc-500 font-normal text-sm">{{$date->shortEnglishMonth}} {{$date->day}}</span>
                    </p>
                    <p>{{$tweet->content}}</p>

                    <form class="flex mt-4 gap-10 tweet_menu">
                      @csrf
                      <button title="Reply" class="cursor-pointer">
                        <svg class="w-[1.5rem] hover:fill-sky-500 fill-white/25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M267.7 576.9C267.7 576.9 267.7 576.9 267.7 576.9L229.9 603.6C222.6 608.8 213 609.4 205 605.3C197 601.2 192 593 192 584L192 512L160 512C107 512 64 469 64 416L64 192C64 139 107 96 160 96L480 96C533 96 576 139 576 192L576 416C576 469 533 512 480 512L359.6 512L267.7 576.9zM332 472.8C340.1 467.1 349.8 464 359.7 464L480 464C506.5 464 528 442.5 528 416L528 192C528 165.5 506.5 144 480 144L160 144C133.5 144 112 165.5 112 192L112 416C112 442.5 133.5 464 160 464L216 464C226.4 464 235.3 470.6 238.6 479.9C239.5 482.4 240 485.1 240 488L240 537.7C272.7 514.6 303.3 493 331.9 472.8z"/></svg>
                      </button>

                      <button
                        type="submit"
                        hx-post="/like/{{$tweet->id}}"
                        title="Like"
                        id="lbtn-{{$tweet->id}}"
                        class="cursor-pointer flex gap-1 items-center">

                        <svg id="like-{{$tweet->id}}" class="w-[1.5rem] fill-red-500 @if (!$is_liked) hidden @endif" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"> <path d="M305 151.1L320 171.8L335 151.1C360 116.5 400.2 96 442.9 96C516.4 96 576 155.6 576 229.1L576 231.7C576 343.9 436.1 474.2 363.1 529.9C350.7 539.3 335.5 544 320 544C304.5 544 289.2 539.4 276.9 529.9C203.9 474.2 64 343.9 64 231.7L64 229.1C64 155.6 123.6 96 197.1 96C239.8 96 280 116.5 305 151.1z"/></svg>

                        <svg id="unlike-{{$tweet->id}}" class="w-[1.5rem] fill-white/25 @if ($is_liked) hidden @endif" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"> <path d="M442.9 144C415.6 144 389.9 157.1 373.9 179.2L339.5 226.8C335 233 327.8 236.7 320.1 236.7C312.4 236.7 305.2 233 300.7 226.8L266.3 179.2C250.3 157.1 224.6 144 197.3 144C150.3 144 112.2 182.1 112.2 229.1C112.2 279 144.2 327.5 180.3 371.4C221.4 421.4 271.7 465.4 306.2 491.7C309.4 494.1 314.1 495.9 320.2 495.9C326.3 495.9 331 494.1 334.2 491.7C368.7 465.4 419 421.3 460.1 371.4C496.3 327.5 528.2 279 528.2 229.1C528.2 182.1 490.1 144 443.1 144zM335 151.1C360 116.5 400.2 96 442.9 96C516.4 96 576 155.6 576 229.1C576 297.7 533.1 358 496.9 401.9C452.8 455.5 399.6 502 363.1 529.8C350.8 539.2 335.6 543.9 320 543.9C304.4 543.9 289.2 539.2 276.9 529.8C240.4 502 187.2 455.5 143.1 402C106.9 358.1 64 297.7 64 229.1C64 155.6 123.6 96 197.1 96C239.8 96 280 116.5 305 151.1L320 171.8L335 151.1z"/></svg>
                        <span id="likesCounts-{{$tweet->id}}" class="text-white/50 text-sm">{{$likes}}</span>
                      </button>
                    </form>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </nav>

          <script>
          document.getElementById("tweets").addEventListener("click", (e) => {
            let btn = e.target.parentElement;
            if (btn.tagName === "svg") { btn = btn.parentElement; }
            const tweet_id = Number(btn.id.split("-")[1]);
            const like = document.getElementById(`like-${tweet_id}`).classList;
            const unlike = document.getElementById(`unlike-${tweet_id}`).classList;
            const likes = document.getElementById(`likesCounts-${tweet_id}`);
            if (like.contains("hidden")) {
              like.remove("hidden");
              unlike.add("hidden");
              likes.innerText = Number(likes.innerText)+1;
            } else {
              like.add("hidden");
              unlike.remove("hidden");
              likes.innerText = Number(likes.innerText)-1;
            }
          });
          </script>
      </div>
    </div>
  </main>
</x-layout>

<style>
::backdrop {
  background-image: linear-gradient(
    0deg,
    black,
    #1e1e1e
    );
  opacity: 0.75;
}
</style>
