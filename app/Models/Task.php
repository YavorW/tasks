<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const type_bug = 1;
    const type_new_feature = 2;
    const type_change = 3;

    const type_bug_text = 'Bug';
    const type_new_feature_text = 'New Feature';
    const type_change_text = 'Change';
    //
    const priority_lowest = 1;
    const priority_low = 2;
    const priority_medium = 3;
    const priority_high = 4;
    const priority_highest = 5;
    
    const priority_lowest_text = 'Lowest';
    const priority_low_text = 'Low';
    const priority_medium_text = 'Medium';
    const priority_high_text = 'High';
    const priority_highest_text = 'Highest';
    //
    const status_to_do = 1;
    const status_in_progress = 2;
    const status_awaiting_upload = 3;
    const status_ready_for_qa = 4;
    const status_resolved = 5;
    const status_not_resolved = 6;
    const status_awaiting_feedback = 7;

    const status_to_do_text = 'To Do';
    const status_in_progress_text = 'In progress';
    const status_awaiting_upload_text = 'Awaiting Upload';
    const status_ready_for_qa_text = 'Ready for QA';
    const status_resolved_text = 'Resolved';
    const status_not_resolved_text = 'Not Resolved';
    const status_awaiting_feedback_text = 'Awaiting Feedback';
    //

    protected $fillable = [
        'project_id',
        'user_id',
        'team',
        'type',
        'status',
        'priority',
        'subject',
        'description',
        'steps_to_reproduce',
        'files',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->addSelect(['username' => User::select('name')
                    ->whereColumn('user_id', 'users.id')
                    ->limit(1),
            ])->latest();
    }

    public function history()
    {
        return $this->hasMany(TaskHistory::class)->latest();
    }

    public function team()
    {
        if ($this->team == Project::team_manager) {
            return Project::team_manager_text;
        } else if ($this->team == Project::team_qa) {
            return Project::team_qa_text;
        } else if ($this->team == Project::team_backend) {
            return Project::team_backend_text;
        } else if ($this->team == Project::team_frontend) {
            return Project::team_frontend_text;
        } else if ($this->team == Project::team_design) {
            return Project::team_design_text;
        } else if ($this->team == Project::team_support) {
            return Project::team_support_text;
        }
        return $this->team;
    }

    public function type()
    {
        if ($this->type == self::type_bug) {
            return self::type_bug_text;
        } else if ($this->type == self::type_new_feature) {
            return self::type_new_feature_text;
        } else if ($this->type == self::type_change) {
            return self::type_change_text;
        }
        return $this->type;
    }

    public function priority()
    {
        if ($this->priority == self::priority_lowest) {
            return self::priority_lowest;
        } else if ($this->priority == self::priority_low) {
            return self::priority_low;
        } else if ($this->priority == self::priority_medium) {
            return self::priority_medium;
        } else if ($this->priority == self::priority_high) {
            return self::priority_high;
        } else if ($this->priority == self::priority_highest) {
            return self::priority_highest;
        }
        return $this->priority;
    }

    public function status()
    {
        if ($this->status == self::status_to_do) {
            return self::status_to_do_text;
        } else if ($this->status == self::status_in_progress) {
            return self::status_in_progress_text;
        } else if ($this->status == self::status_awaiting_upload) {
            return self::status_awaiting_upload_text;
        } else if ($this->status == self::status_ready_for_qa) {
            return self::status_ready_for_qa_text;
        } else if ($this->status == self::status_resolved) {
            return self::status_resolved_text;
        } else if ($this->status == self::status_not_resolved) {
            return self::status_not_resolved_text;
        } else if ($this->status == self::status_awaiting_feedback) {
            return self::status_awaiting_feedback_text;
        }
        return $this->status;
    }

    public function files()
    {
        return json_decode($this->files, 1);
    }
    public function asset($path)
    {
        return getProjectUrl($path);
    }
}
