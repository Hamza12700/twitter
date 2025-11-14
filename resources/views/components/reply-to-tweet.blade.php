@use('Carbon\Carbon')

<dialog class="border w-full max-w-[40rem] fixed top-1 border-zinc-400 md:mt-20 bg-black text-white mx-auto rounded-md" id="dialog">
  <form hx-on::after-request="window.location.reload()" hx-post="/reply" class="p-2">
    @csrf

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
          <span class="ml-1 text-zinc-500 font-normal text-sm"><span>@</span>{{$user->name}} ·</span>
          <span class="text-zinc-500 font-normal text-sm">{{$date->shortEnglishMonth}} {{$date->day}}</span>
        </p>
        <p>{{$tweet->content}}</p>

        <p class="text-white/40 mt-3 font-semibold border-b border-white/30 pb-2">Replying to <span class="text-sky-500"><span>@</span>{{$user->name}}</p>
      </div>
    </div>

    <div class="mt-5 flex">
      <input name="tweeted_by" type="hidden" value="{{Auth::user()->id}}"/>
      <input name="tweet_id"   type="hidden" value="{{$tweet->id}}"/>
      <img class="w-[3rem] h-[3rem] object-cover self-start rounded-full"
        src="{{Auth::user()->profile_picture}}" />
      <textarea
        class="p-2 rounded-md focus:outline-none w-full text-lg mb-3 h-[7rem]"
        required
        placeholder="Post your reply"
        style="resize: none;" required name="content"></textarea>
    </div>

    <button class="bg-white ml-auto text-black block px-4 font-semibold text-lg py-2
      rounded-lg cursor-pointer" type="submit">Reply</button>
  </form>
</dialog>

<script>
document.getElementById("dialog").showModal();
</script>
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
