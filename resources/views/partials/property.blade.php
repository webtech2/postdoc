                            <tr class="collapse show prop">
                                <td><a href="{{ url('property', $prop->md_id) }}">{{$prop->md_name}}</a></td>
                                <td><a href="{{ url('property', $prop->md_id) }}">{{$prop->md_value}}</a></td>
                                <td>
                                    <form action="{{action('PropertyController@destroy', $prop->md_id)}}" method="post" class="delete-frm float-right" data-confirm="Are you sure to delete property: '{{$prop->md_name}}'?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                    <a class="btn btn-success float-right" href="{{ action('PropertyController@edit', $prop->md_id) }}">Edit</a> 
                                </td>                            
                            </tr>