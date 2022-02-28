<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    use HasFactory;
    
    protected $fillable = ['task_id', 'user_id', 'status'];
    
    protected $appends = [ 'status_text' ];

    public function task() 
    {
        return $this->belongsTo(Task::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusTextAttribute()
    {
        if ($this->status == Task::status_to_do) {
            return Task::status_to_do_text;
        } else if ($this->status == Task::status_in_progress) {
            return Task::status_in_progress_text;
        } else if ($this->status == Task::status_awaiting_upload) {
            return Task::status_awaiting_upload_text;
        } else if ($this->status == Task::status_ready_for_qa) {
            return Task::status_ready_for_qa_text;
        } else if ($this->status == Task::status_resolved) {
            return Task::status_resolved_text;
        } else if ($this->status == Task::status_not_resolved) {
            return Task::status_not_resolved_text;
        } else if ($this->status == Task::status_awaiting_feedback) {
            return Task::status_awaiting_feedback_text;
        }
        return $this->status;
    }
}
