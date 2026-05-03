<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ActivityLogViewer extends Component
{
    use WithPagination;

    public function getModelArabicName($modelClass)
    {
        $modelName = class_basename($modelClass);

        $map = [
            'form' => 'عملية شراء',
            'User' => 'مستخدم',
        ];

        return $map[$modelName] ?? $modelName;
    }

    public function getFormattedChanges($log)
    {
        if ($log->event !== 'updated') {
            return [];
        }

        $modelName = class_basename($log->subject_type);
        $attributes = $log->properties['attributes'] ?? [];
        $old = $log->properties['old'] ?? [];

        $changedFields = array_keys(array_diff_assoc($attributes, $old));
        $changesArray = [];

        $fieldMap = [
            'form' => [
                'full_name' => 'الإسم',
                'national_id' => 'رقم الهوية',
                'id_version_number' => 'رقم النسخة',
                'karat' => 'العيار',
                'weight' => 'الوزن',
                'date_of_birth' => 'تاريخ الميلاد',
                'sale_price' => 'سعر الشراء',
                'unit_type' => 'نوع الوحدة',
                'description' => 'الوصف',
                'product_image' => ' الصورة',
            ],
            'User' => [
                'name' => 'الاسم ',
                'email' => ' الإيميل',
                'end_date' => ' تاريخ الإشتراك',

            ]
        ];

      foreach ($changedFields as $field) {
            if ($field === 'updated_at') continue;

            if (isset($fieldMap[$modelName][$field])) {

                $oldValue = $old[$field] ?? 'فارغ';
                $newValue = $attributes[$field] ?? 'فارغ';

                if ($field === 'date_of_birth' || $field === 'end_date') {
                    $oldValue = $oldValue !== 'فارغ' ? \Carbon\Carbon::parse($oldValue)->format('Y-m-d') : $oldValue;
                    $newValue = $newValue !== 'فارغ' ? \Carbon\Carbon::parse($newValue)->format('Y-m-d') : $newValue;
                }


                $changesArray[] = [
                    'label' => $fieldMap[$modelName][$field],
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changesArray;
    }

    public function render()
    {
        $currentUser = Auth::user();

        $query = Activity::with('causer')->oldest();

        // if it was super admin do nonothing he will see every one
        if ($currentUser->hasRole('Super Admin')) {

        // else the admin will see his self and cilds
        } elseif ($currentUser->hasRole('Admin')) {

            $employeeIds = User::where('admin_id', $currentUser->id)->pluck('id')->toArray();

            $allowedIds = array_merge([$currentUser->id], $employeeIds);

            $query->whereIn('causer_id', $allowedIds);

        } else {
            // here the user will see himself only not anyone else

            $query->where('causer_id', $currentUser->id);

        }

        $logs = $query->paginate(15);

        return view('livewire.activity-log-viewer', [
            'logs' => $logs
        ])->layout('layoutscreen.app');
    }
}
