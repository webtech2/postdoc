                            <tr class="collapse show change">
                                <td><a href="{{ url('mapping', $mapping->mp_id) }}">{{$mapping->buildOperation()}}</a></td>
                                <td>
                                    <form action="{{action('MappingController@destroy', $mapping->mp_id)}}" method="post" class="delete-frm float-right" data-confirm="Are you sure to delete mapping for: '{{$mapping->targetDataItem->dataSet->ds_name}}.{{$mapping->targetDataItem->di_name}}'?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                    <a class="btn btn-success float-right" href="{{ action('MappingController@edit', $mapping->mp_id) }}">Edit</a> 
                                </td>
                            </tr>