                            <tr>
                                <td><a href="{{ url('dataitem', $item->di_id) }}">{{ $item->di_name }}</a></td>
                                <td><a href="{{ url('dataitem', $item->di_id) }}">{{ $item->itemType->tp_type}}</a></td>
                                <td>
                                    <a href="{{ action('DataItemController@edit', $item->di_id) }}">Edit</a> |
                                    <a href="{{ action('DataItemController@destroy', $item->di_id) }}">Delete</a>
                                </td>                            
                            </tr>
