@if ($process->changeAdaptationScenario->cas_parentscenario_id==null) 
     <tr class="collapse show process">
         <td colspan='3'><strong>Scenario steps</strong></td>
     </tr>
@endif    
    <tr class="collapse show process">
        <td>{{$process->changeAdaptationScenario->changeAdaptationOperation->cao_operation}}</td>
        <td> 
            @if ($process->changeAdaptationScenario->changeAdaptationOperation->type->tp_id == 'COP0000001' 
                and $process->statusType->tp_id == 'CIP0000001')
                <a class="btn btn-success float-right" 
                   href="{{ action('AdaptationController@setChangeAdaptationProcessExecuted', $process->cap_id)}}"
                   title="{{$process->statusType->tp_type}}">Set executed</a> 
            @else
                {{$process->statusType->tp_type}}
            @endif
        </td>
        <td>{{$process->changeAdaptationScenario->changeAdaptationOperation->type->tp_type}}</td>
        <th>Condition type</th>
        <th>Condition</th>
        <th>Status</th>
    </tr>
    @foreach ($process->changeAdaptationScenario->caConditionMappings as $cond)
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>{{$cond->changeAdaptationCondition->type->tp_type}}</td>
        <td>{{$cond->changeAdaptationCondition->cac_condition}}</td>
        <td>
            @if ($cond->changeAdaptationCondition->type->tp_id=='CON0000002')
                @if ($process->change->caManualConditionFulfillments()
                    ->where('camcf_condition_id',$cond->cacm_condition_id)
                    ->first()
                    ->fulfillmentStatus->tp_id=='MCF0000001')
                    <a class="btn btn-success float-right" 
                        href="{{ action('AdaptationController@setManualConditionFulfilled', ['ch_id'=>$process->change->ch_id, 'cond_id'=>$cond->cacm_condition_id])}}"
                        title="{{$process->change->caManualConditionFulfillments()
                                    ->where('camcf_condition_id',$cond->cacm_condition_id)
                                    ->first()
                                    ->fulfillmentStatus->tp_type}}">
                        Set manual condition fulfilled</a> 
                @else
                    {{$process->change->caManualConditionFulfillments()
                    ->where('camcf_condition_id',$cond->cacm_condition_id)
                    ->first()
                    ->fulfillmentStatus->tp_type}}
                @endif
            @endif
        </td>
    @endforeach
    </tr>

