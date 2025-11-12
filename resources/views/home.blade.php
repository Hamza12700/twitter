@use('Carbon\Carbon')

<x-layout>
  <main class="max-w-[80rem] px-2 my-5 mx-auto">
    <div class="flex">
      <x-side-navbar :name="Auth::user()->name"/>

      @php $user_profile_picture = Auth::user()->profile_picture; @endphp
      <div class="w-full max-w-[50rem]">
        <form id="tweet_form" class="border-b border-white pb-5" hx-post="/tweet">
          @csrf
          <input name="tweeted_by" type="hidden" value="{{$user_id}}" />
          <div class="flex gap-3">
            <img class="w-[3rem] h-[3rem] object-cover self-start rounded-full bg-white" src="{{$user_profile_picture}}" />
            <textarea
              class="p-2 rounded-md focus:outline-none w-full text-lg mb-3 h-[7rem]"
              required
              placeholder="What's on your mind"
              style="resize: none;" required name="content"></textarea>
          </div>
          <button
            class="bg-white ml-auto text-black block px-4 font-semibold text-lg py-2 rounded-lg cursor-pointer"
            type="submit">Tweet</button>
        </form>

        <script>
        document.body.addEventListener("htmx:afterRequest", function (e) {
          if (e.target.id === "tweet_form") {
            e.target.reset();
            window.location.reload();
          }
        });
        </script>

        <div id="tweets">
          @foreach ($tweets as $tweet)
          <div class="py-5 border-b border-white border-zinc-500">
            @php
            $user = DB::selectOne("select * from users where id = ?", [$tweet->tweeted_by]);
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
                  <span class="ml-1 text-zinc-500 font-normal text-sm"><span>@</span>{{$user->name}} ·</span>
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
    </div>
  </main>
</x-layout>
