<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $description
 * @property string $link
 * @property string $settings
 */
class Project extends Model
{
    use HasFactory;

    const team_manager = 1,
    team_qa = 2,
    team_backend = 3,
    team_frontend = 4,
    team_design = 5,
    team_support = 6;

    const team_manager_text = 'Менижъри',
    team_qa_text = 'QA',
    team_backend_text = 'Back-End',
    team_frontend_text = 'Front-End',
    team_design_text = 'Дизайн',
    team_support_text = 'Поддръжка';

    protected $fillable = [
        'name', 'description', 'link', 'settings',
    ];

    public function users($distinct = false) 
    {
        $builder = $this->belongsToMany(User::class,'project_user');
        
        return $distinct ? $builder->distinct() : $builder->withPivot('role');
        
    }

    public function tasks() 
    {
        return $this->hasMany(Task::class);
    }

    public function getSettingsAttribute($value)
    {
        return json_decode($value, 1);
    }

    public function setSettingsAttribute($value)
    {
        $this->attributes['settings'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

}
