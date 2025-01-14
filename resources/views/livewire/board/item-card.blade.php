<li class="pb-5 pt-5 first:pt-0 group">
    <div class="inline-block flex space-x-3">
        <div class="flex flex-col text-center -space-y-1">
            <button wire:click="toggleUpvote" class="hover:text-primary-500">
                <x-heroicon-o-chevron-up class="w-5 h-5" />
            </button>

            <span class="">{{ $item->total_votes }}</span>
        </div>

        <a href="{{ route('projects.items.show', [$project, $item]) }}" class="flex-1">
            <p class="font-bold text-lg group-hover:text-primary-500">{{ $item->title }}</p>
            <p>{{ $item->excerpt }}</p>
        </a>

        <div>
            {{ $comments }} {{ trans_choice('messages.comments', $comments) }}
        </div>
    </div>
</li>
