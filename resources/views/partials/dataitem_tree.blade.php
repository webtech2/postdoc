<li>{{ $item->di_name }} : {{ $item->itemType->tp_type}} : {{ $item->di_id }}</li>
	@if (count($item->relationships) > 0)
	    <ul>
	    @foreach($item->relationships as $rel)
                @foreach($rel->relationshipElements as $re)
                    @include('partials.dataitem_tree', ['item' => $re->childDataItem])
                @endforeach
	    @endforeach
	    </ul>
	@endif