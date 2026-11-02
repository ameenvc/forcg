<?php

namespace App\Traits;
use illuminate\Support\Str;

trait HasCustomFieldFillable
{
    public function fillable(array $fields)
    {
        $this->fillable = array_unique(array_merge($this->fillable, $fields));
        return $this;
    }

    public function loadCustomFields()
    {
        $modelName = class_basename($this);
        $fields = \App\CustomField::where('model_name', $modelName)->pluck('field_name')->toArray();
        $sanitizedFields = [];
        foreach ($fields as $field) {
            $sanitizedFields[] = Str::slug($field, '_');
        }
        $this->fillable($sanitizedFields);
    }
}