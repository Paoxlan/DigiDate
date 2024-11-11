<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait AuditTrailable
{
    protected function getHiddenAuditTrailAttributes(): array
    {
        return [
            'updated_at'
        ];
    }

    public function auditTrailJson(Model|array $models): string
    {
        $hiddenAttributes = $this->getHiddenAuditTrailAttributes();

        $modelArray = null;
        if (is_array($models)) {
            foreach ($models as $model) {
                $className = $model::class;
                $model = $model->withoutRelations()->toArray();
                $modelArray[$className] = array_filter($model, function ($key) use ($hiddenAttributes) {
                    return !in_array($key, $hiddenAttributes);
                }, ARRAY_FILTER_USE_KEY);
            }
        } else {
            $modelArray = $models->withoutRelations()->toArray();
            $modelArray = array_filter($modelArray, function ($key) use ($hiddenAttributes) {
                return !in_array($key, $hiddenAttributes);
            }, ARRAY_FILTER_USE_KEY);
        }

        return json_encode($modelArray);
    }
}
