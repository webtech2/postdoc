<?php

namespace App\Http\Requests;

use App\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreDataSet extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData() {
        if ($this['format'] == Type::where('tp_type','Relational')->first()->tp_id) { 
            if (Str::contains($this['ds_name'],'.')) {
                return array_merge(
                    $this->all(),
                    [
                        'owner' => Str::before($this['ds_name'],'.'),
                        'table' => Str::after($this['ds_name'],'.'),
                    ]
                );
            }     
        }
        return $this->all();
    }    
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this['format'] == Type::where('tp_type','Relational')->first()->tp_id) {
            $objcolumn='ds_'.$this['object'].'_id';
            $id = $this['id'];     
            $owner = Str::before($this['ds_name'],'.');
            return [
                'object'  => [
                    'required'
                ],
                'id'  => [
                    'required'
                ],
                'type'  => [
                    'required',
                    'size:10',
                    'exists:types,tp_id'
                ],
                'format'  => [
                    'required',
                    'size:10',
                    'exists:types,tp_id'
                ],
                'velocity'  => [
                    'required',
                    'size:10',
                    'exists:types,tp_id'
                ],                
                'owner' => [
                    'required'
                ],
                'frequency' => [
                    'required'
                ],                
                'table' => [
                    'required',
                    Rule::exists('all_tables','table_name')->where(function ($query) use ($owner) {
                        return $query->where('owner', $owner);
                    }),                            
                ],
                'ds_name' => [
                    'required',
                    'min:1',
                    'max:71',
                    Rule::unique('dataset','ds_name')->where(function ($query) use ($objcolumn,$id) {
                        return $query->where($objcolumn, $id)->whereNull('ds_deleted');
                    }),
                ],  
            ];
        }
        else return [];
    }
       
}
