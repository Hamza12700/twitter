@use('Carbon\Carbon')

<x-layout>

  <script>
  function clean_up() {
    const textarea = document.querySelector("form textarea");
    textarea.value = "";
    window.location.reload();
  }
  </script>

  <main class="max-w-[80rem] px-2 my-5 mx-auto">
    <div class="flex">
      <aside class="w-fit mr-3 md:mr-5 border-neutral-500 border-r pr-2 md:pr-10">
        <a class="flex my-4 items-center gap-2 text-2xl hover:underline" href="/home">
          <svg class="fill-white w-[2rem]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M304 70.1C313.1 61.9 326.9 61.9 336 70.1L568 278.1C577.9 286.9 578.7 302.1 569.8 312C560.9 321.9 545.8 322.7 535.9 313.8L527.9 306.6L527.9 511.9C527.9 547.2 499.2 575.9 463.9 575.9L175.9 575.9C140.6 575.9 111.9 547.2 111.9 511.9L111.9 306.6L103.9 313.8C94 322.6 78.9 321.8 70 312C61.1 302.2 62 287 71.8 278.1L304 70.1zM320 120.2L160 263.7L160 512C160 520.8 167.2 528 176 528L224 528L224 424C224 384.2 256.2 352 296 352L344 352C383.8 352 416 384.2 416 424L416 528L464 528C472.8 528 480 520.8 480 512L480 263.7L320 120.3zM272 528L368 528L368 424C368 410.7 357.3 400 344 400L296 400C282.7 400 272 410.7 272 424L272 528z"/></svg>
          <span class="hidden md:block">Home</span>
        </a>

        <a class="flex my-4 items-center gap-2 text-2xl hover:underline"
          href="/notifications">
          <svg class="fill-white w-[2rem]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M320 64C306.7 64 296 74.7 296 88L296 97.7C214.6 109.3 152 179.4 152 264L152 278.5C152 316.2 142 353.2 123 385.8L101.1 423.2C97.8 429 96 435.5 96 442.2C96 463.1 112.9 480 133.8 480L506.2 480C527.1 480 544 463.1 544 442.2C544 435.5 542.2 428.9 538.9 423.2L517 385.7C498 353.1 488 316.1 488 278.4L488 263.9C488 179.3 425.4 109.2 344 97.6L344 87.9C344 74.6 333.3 63.9 320 63.9zM488.4 432L151.5 432L164.4 409.9C187.7 370 200 324.6 200 278.5L200 264C200 197.7 253.7 144 320 144C386.3 144 440 197.7 440 264L440 278.5C440 324.7 452.3 370 475.5 409.9L488.4 432zM252.1 528C262 556 288.7 576 320 576C351.3 576 378 556 387.9 528L252.1 528z"/></svg>
          <span class="hidden md:block">Notifications</span>
        </a>

        <a class="flex my-4 items-center gap-2 text-2xl hover:underline"
          href="/messages">
          <svg class="fill-white w-[2rem]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M267.7 576.9C267.7 576.9 267.7 576.9 267.7 576.9L229.9 603.6C222.6 608.8 213 609.4 205 605.3C197 601.2 192 593 192 584L192 512L160 512C107 512 64 469 64 416L64 192C64 139 107 96 160 96L480 96C533 96 576 139 576 192L576 416C576 469 533 512 480 512L359.6 512L267.7 576.9zM332 472.8C340.1 467.1 349.8 464 359.7 464L480 464C506.5 464 528 442.5 528 416L528 192C528 165.5 506.5 144 480 144L160 144C133.5 144 112 165.5 112 192L112 416C112 442.5 133.5 464 160 464L216 464C226.4 464 235.3 470.6 238.6 479.9C239.5 482.4 240 485.1 240 488L240 537.7C272.7 514.6 303.3 493 331.9 472.8z"/></svg>
          <span class="hidden md:block">Messages</span>
        </a>

        <a class="flex my-4 items-center gap-2 text-2xl hover:underline"
          href="/profile">
          <svg class="fill-white w-[2rem]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M240 192C240 147.8 275.8 112 320 112C364.2 112 400 147.8 400 192C400 236.2 364.2 272 320 272C275.8 272 240 236.2 240 192zM448 192C448 121.3 390.7 64 320 64C249.3 64 192 121.3 192 192C192 262.7 249.3 320 320 320C390.7 320 448 262.7 448 192zM144 544C144 473.3 201.3 416 272 416L368 416C438.7 416 496 473.3 496 544L496 552C496 565.3 506.7 576 520 576C533.3 576 544 565.3 544 552L544 544C544 446.8 465.2 368 368 368L272 368C174.8 368 96 446.8 96 544L96 552C96 565.3 106.7 576 120 576C133.3 576 144 565.3 144 552L144 544z"/></svg>
          <span class="hidden md:block">Profile</span>
        </a>
      </aside>

      @php $user_profile_picture = Auth::user()->profile_picture; @endphp
      <div class="max-w-[40rem]">
        <form hx-on:htmx:after-request="clean_up()" class="border-b border-white pb-5" hx-post="/tweet">
          @csrf
          <input name="tweeted_by" type="hidden" value="{{$user_id}}" />
          <div class="flex gap-3">
            <img class="w-[3rem] self-start rounded-full bg-white" src="{{$user_profile_picture}}" />
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

        <div id="tweets">
          @foreach ($tweets as $tweet)
          <div class="py-5 border-b border-white border-zinc-500">
            @php
            $user = DB::selectOne("select * from users where id = ?", [$tweet->tweeted_by]);
            $date = Carbon::parse($tweet->created_at);
            @endphp

            <div class="flex gap-2">
              <img class="w-[3rem] self-start rounded-full bg-white" src="{{$user_profile_picture}}" />
              <div>
                <p class="font-bold">
                  <a class="hover:underline" href="/user/{{$user->name}}">{{$user->nickname}}</a>
                  <span class="ml-1 text-zinc-500 font-normal text-sm"><span>@</span>{{$user->name}} ·</span>
                  <span class="text-zinc-500 font-normal text-sm">{{$date->shortEnglishMonth}} {{$date->day}}</span>
                </p>
                <p>{{$tweet->content}}</p>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </main>
</x-layout>
