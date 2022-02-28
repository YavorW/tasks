<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository
{
    public static function find($id)
    {
        return Comment::where('id', $id)->first();
    }
    
    public static function all($task = null)
    {
        return Comment::orderBy('key', 'asc')
        ->when($task, function($q) use($task) {
            $q->where('task_id', $task->id);
        })
        ->get();
    }

    public static function create(array $input)
    {
        $comment = Comment::create($input);
        $comment->username = $comment->user->name;
        return $comment;
    }

    public static function update($comment, array $input)
    {
        $comment->update($input);
        return $comment;
    }
    
    public static function delete($comment)
    {
        $comment->delete();
    }
}
