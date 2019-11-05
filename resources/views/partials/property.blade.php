                            <tr class="collapse show prop">
                                <td><a href="{{ url('property', $prop->md_id) }}">{{$prop->md_name}}</a></td>
                                <td><a href="{{ url('property', $prop->md_id) }}">{{$prop->md_value}}</a></td>
                                <td>
                                    <a href="{{ action('PropertyController@edit', $prop->md_id) }}">Edit</a> |
                                    <a href="{{ action('PropertyController@destroy', $prop->md_id) }}">Delete</a>
                                </td>                            
                            </tr>