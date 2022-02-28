<?php
use App\Models\Project;
use App\Models\Task;
?>
  <td colspan="4"></td>
  <td>
    <x-form.select name="filter_type" wire:model="filter_type">
      <option value="">Филтрирай</option>
      <option value="{{ Task::type_bug }}">Bug</option>
      <option value="{{ Task::type_new_feature }}">New Feature</option>
      <option value="{{ Task::type_change }}">Change</option>
    </x-form.select>
  </td>
  <td>
    <x-form.select name="filter_priority" wire:model="filter_priority">
      <option value="">Филтрирай</option>
      <option value="{{ Task::priority_lowest }}">Lowest</option>
      <option value="{{ Task::priority_low }}">Low</option>
      <option value="{{ Task::priority_medium }}">Medium</option>
      <option value="{{ Task::priority_high }}">High</option>
      <option value="{{ Task::priority_highest }}">Highest</option>
    </x-form.select>
  </td>
  <td>
    <x-form.select name="filter_status" wire:model="filter_status">
      <option value="">Филтрирай</option>
      <option value="{{ Task::status_to_do }}">To Do</option>
      <option value="{{ Task::status_in_progress }}">In progress</option>
      <option value="{{ Task::status_awaiting_upload }}">Awaiting Upload</option>
      <option value="{{ Task::status_ready_for_qa }}">Ready for QA</option>
      <option value="{{ Task::status_resolved }}">Resolved</option>
      <option value="{{ Task::status_not_resolved }}">Not Resolved</option>
      <option value="{{ Task::status_awaiting_feedback }}">Awaiting Feedback</option>
    </x-form.select>
  </td>
  <td>

    <x-form.select name="filter_team" wire:model="filter_team">
      <option value="">Филтрирай</option>
      <option value="{{ Project::team_manager }}">Менижъри</option>
      <option value="{{ Project::team_qa }}">QA</option>
      <option value="{{ Project::team_backend }}">Back-End</option>
      <option value="{{ Project::team_frontend }}">Front-End</option>
      <option value="{{ Project::team_design }}">Дизайн</option>
      <option value="{{ Project::team_support }}">Поддръжка</option>
    </x-form.select>
  </td>
  <td>
    <x-form.select iname="filter_user" wire:model="filter_user">
      <option value="">Филтрирай</option>
      @if ($users)
        @foreach ($users as $user)
          <option value="{{ $user->id }}">
            {{ $user->name }}</option>
        @endforeach
      @endif
    </x-form.select>
  </td>
  <td>
  </td>
