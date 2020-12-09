@if (count($timelines) > 0)
    <ul class="list-unstyled">
        @foreach ($timelines as $timeline)
            <li class="media mb-3">
                <div class="media-body">
                    <div>
                        {{-- 投稿の所有者のユーザ詳細ページへのリンク --}}
                        {!! link_to_route('users.show', $timeline->user->name, ['user' => $timeline->user->id]) !!}
                        <span class="text-muted">posted at {{ $timeline->created_at }}</span>
                    </div>
                    <div>
                        {{-- 投稿内容 --}}
                        <p class="mb-0">{!! nl2br(e($timeline->content)) !!}</p>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $timelines->links() }}
@endif