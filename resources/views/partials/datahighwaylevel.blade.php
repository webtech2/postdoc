                        <tr class="collapse show level">
                            <td><a href="{{ action('DataHighwayController@show', $dhlevel->hl_id) }}">{{ $dhlevel->hl_name }}</a></td>
                            <td><a href="{{ action('DataHighwayController@show', $dhlevel->hl_id) }}">{{$dhlevel->hl_created}}</a></td>
                            <td>
                                <form action="{{action('DataHighwayController@destroy', $dhlevel->hl_id)}}" method="post" class="delete-frm float-right" data-confirm="Are you sure to delete data highway level: '{{$dhlevel->hl_name }}'?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                                <a class="btn btn-success float-right" href="{{ action('DataHighwayController@edit', $dhlevel->hl_id) }}">Edit</a> 
                            </td>                            

                        </tr>
