<div class="form-group">
    <label class="control-label">{{ trans('meridien.imports.role') }}<span class="required"> * </span></label>
    {!! Form::select('settings[role_id]', $roles, isset($imports)? $imports->settings['role_id'] : null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    <label class="control-label">{{ trans('meridien.imports.step') }}<span class="required"> * </span></label>
    {!! Form::select('settings[step_id]', $steps, isset($imports)? $imports->settings['step_id'] : null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    <label class="control-label">{{ trans('meridien.imports.status') }}<span class="required"> * </span></label>
    {!! Form::select('settings[status_id]', $statuses, isset($imports)? $imports->settings['status_id'] : null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    <label class="control-label">{{ trans('meridien.imports.user') }}<span class="required"> * </span></label>
    {!! Form::select('settings[user_id]', $employees->pluck('name', 'id'), isset($imports)? $imports->settings['user_id'] : null, ['class' => 'form-control', 'required']) !!}
    <small>Esta importação vai para a base de quem?</small>
</div>

<hr>

<div class="form-group">
    <label class="control-label">{{ trans('meridien.imports.tasks.type') }}<span class="required"> * </span></label>
    {!! Form::select('settings[task_type_id]', $task_types, isset($imports)? $imports->settings['task_type_id'] : null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    <label class="control-label">{{ trans('meridien.imports.tasks.status') }}<span class="required"> * </span></label>
    {!! Form::select('settings[task_status_id]', $task_statuses, isset($imports)? $imports->settings['task_status_id'] : null, ['class' => 'form-control', 'required']) !!}
</div>
