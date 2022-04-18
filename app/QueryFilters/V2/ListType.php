<?php
namespace App\QueryFilters\V2;

use App\QueryFilters\Filter;
use function foo\func;

class ListType extends Filter
{
    protected $field_name = 'list';

    protected function applyFilter($builder)
    {
        $not_reestr_statuses = \App\CitizenStatus::whereNull('is_reestr')->pluck('id');
        $reestr_condition = "(citizen_status_ids is null or not (";
        $i = 0;
        foreach ($not_reestr_statuses as $not_reestr_status) {
            $reestr_condition .= "'".$not_reestr_status."'". " = any (string_to_array(citizen_status_ids, ','))";
            if(++$i != count($not_reestr_statuses)) $reestr_condition .= " or "; else $reestr_condition .= "))";
        }
        switch (request($this->field_name)) {
            case 'all': return $builder;
            case 'reestr': return $builder->wherehas('citizenAction', function ($query) use ($reestr_condition) {
                $query->select('pin')->where('status', 0)->where('is_employer', 'false')->whereRaw($reestr_condition);
            });
            case 'notebook': return $builder->wherehas('citizenAction', function ($query) {
                $query->select('pin');
                $query->where('status', 1);
            })->whereHas('survey', function ($query) {
                $query->where('need_work', 1)->orWhereNotNull('sip_problem_id');
            });
            case 'survey': return $builder->wherehas('citizenAction', function ($query) {
                $query->select('pin');
                $query->where('status', '!=', 0);
            });
            case 'out-notebook': return $builder->wherehas('citizenAction', function ($query) {
                $query->select('pin')->where('status', 2);
                if($confirm = request('out_type')){
                    $confirm = $confirm == 2 ? null : 1;
                    $query->whereHas('survey', function ($q) use ($confirm) {
                        $q->whereHas('citizenHistory', function($query2)use($confirm){
                            $query2->where('confirmed', $confirm);
                        });
                    });
                }
            });
        }
    }
}
