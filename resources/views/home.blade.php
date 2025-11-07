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
      <x-side-navbar :name="Auth::user()->name"/>

      @php $user_profile_picture = Auth::user()->profile_picture; @endphp
      <div class="w-full max-w-[50rem]">
        <form hx-on:htmx:after-request="clean_up()" class="border-b border-white pb-5" hx-post="/tweet">
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

        <div id="tweets">
          @foreach ($tweets as $tweet)
          <div class="py-5 border-b border-white border-zinc-500">
            @php
            $user = DB::selectOne("select * from users where id = ?", [$tweet->tweeted_by]);
            $date = Carbon::parse($tweet->created_at);
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
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </main>
</x-layout>
