                            <tr class="collapse show set">
                                <td><a href="{{ url('dataset', $dset->ds_id) }}" title="{{$dset->ds_description}}">{{$dset->ds_name}}</a></td>
                                <td><a href="{{ url('dataset', $dset->ds_id) }}">{{$dset->formatType->tp_type}}</a></td>
                                <td><a href="{{ url('dataset', $dset->ds_id) }}">{{$dset->ds_frequency}}</a></td>
                                <td><a href="{{ url('dataset', $dset->ds_id) }}">{{$dset->velocityType->tp_type}}</a></td>
                                <td><a href="{{ url('dataset', $dset->ds_id) }}">{{$dset->lastChanged()}}</a></td>
                                <td>
                                    <form action="{{action('DataSetController@destroy', $dset->ds_id)}}" method="post" class="delete-frm float-right" data-confirm="Are you sure to delete data set: '{{$dset->ds_name}}'?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                    <a class="btn btn-success float-right" href="{{ action('DataSetController@edit', $dset->ds_id) }}">Edit</a> 
                                </td>
                            </tr>