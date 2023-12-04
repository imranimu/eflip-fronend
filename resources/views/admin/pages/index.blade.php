@extends('admin/master')
@section('content')

		<div class="box_general">
			<div class="header_box">
				<h2 class="d-inline-block">Pages</h2>
				<div class="filter">
          <div class="btn-group">
            <a class="btn btn-primary" href="{{ route('admin.pages.create') }}"><i class="fa fa-plus"></i> Add</a>
            {{-- <a class="btn btn-warning" href="{{ url('/admin/product/trash') }}"><i class="fa fa-trash"></i> Trash</a>--}}
          </div>
        <!--	<select name="orderby" class="selectbox">
						<option value="Any time">Any time</option>
						<option value="Latest">Latest</option>
						<option value="Oldest">Oldest</option>
					</select> -->
				</div>
			</div>
			<div class="list_general">
				<ul>
            @forelse ($pages as $page)
              <li>
                  <h4>{{$page->title }}</h4>
                  <ul class="buttons">
                    {{--<li><a href="{{ url('/product/'.$product->id) }}" target="_blank" class="btn_1 bg-info"><i class="fa fa-fw fa-eye"></i> View</a></li>--}}
                      <li><a href="{{ route('admin.pages.edit',$page->id) }}" class="btn_1 bg-info"><i class="fa fa-fw fa-edit"></i> Edit</a></li>
                      <li><a href="{{ url('/admin/delete/'.$page->id) }}" class="btn_1 yellow delete wishlist_close"><i class="fa fa-fw fa-times-circle-o"></i> Delete</a></li>
                  </ul>
              </li>
            @empty
              <h4 class='text-danger'>No page is added</h4>
            @endforelse
				</ul>
			</div>
		</div>
    
@endsection
