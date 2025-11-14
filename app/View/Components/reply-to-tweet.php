<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Tweet;
use App\Models\User;

class reply-to-tweet extends Component
{
  public function __construct(
    public Tweet $tweet,
    public User  $user,
  ) {}

  public function render(): View|Closure|string
  {
    return view('components.reply-to-tweet');
  }
}
