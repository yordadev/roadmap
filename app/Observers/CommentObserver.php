<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Comment;
use App\Notifications\CommentHasReplyNotification;
use Xetaio\Mentions\Parser\MentionParser;
use App\Notifications\Item\ItemHasNewCommentNotification;

class CommentObserver
{
    public function created(Comment $comment)
    {
        $parser = new MentionParser($comment, [
            'regex_replacement' => [
                '{character}' => '@',
                '{pattern}' => '[A-Za-z0-9_-]',
                '{rules}' => '{4,20}'
            ],
        ]);

        $content = $parser->parse($comment->content);

        $comment->updateQuietly([
            'content' => $content,
        ]);

        $userIds = $comment->item?->votes()
            ->subscribed()
            ->where('user_id', '!=', auth()->id()) // Don't get the current user, they obviously already know about the new comment
            ->pluck('user_id') ?? collect();

        User::query()->whereIn('id', $userIds->toArray())->get()->each(function (User $user) use ($comment) {
            $user->notify(new ItemHasNewCommentNotification($comment));
        });

        $comment->parent?->user->notify(new CommentHasReplyNotification($comment));
    }

    public function deleting(Comment $comment)
    {
        $comment->mentions()->delete();
    }
}
