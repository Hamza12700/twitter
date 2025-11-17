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

        <div id="tweets"></div>
        <img class="htmx-indicator w-[2rem] mx-auto mt-4" alt="loading..." id="spinner" src="/bars.svg" />
      </div>
    </div>
  </main>

  <script hx-indicator="#spinner" hx-get="/tweets?c=0" hx-trigger="revealed" hx-target="#tweets">
  document.body.addEventListener("htmx:afterRequest", function (e) {
    if (e.target.id === "tweet_form") {
      e.target.reset();
      window.location.reload();
    }
  });

  document.getElementById("tweets").addEventListener("click", (e) => {
    let btn = e.target.parentElement;
    if (btn.tagName === "svg") { btn = btn.parentElement; }
    if (btn.title !== "Like") { return; }
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
</x-layout>
