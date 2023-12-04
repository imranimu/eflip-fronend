@extends('admin/master')


@section('content')
   <div class="row">
    <div class="col-md-8">
      <h2>
        @if(!empty($_GET['month']) && !empty($_GET['year'])) 

          {{ date('F', mktime(0, 0, 0, $_GET['month'], 1)) }} 
          {{ $_GET['year'] }}

          @if($_GET['month']==date('m')-1 && $_GET['year']==date('Y'))
            <span class="text-info">Accepting Ratings</span>
          @endif



        @else 
          {{ date('M').' '.date('Y') }} 
          <span class="text-success">Accepting Essay Submission</span>
        @endif
      </h2>
    </div>
    <div class="col-md-4 text-right">
      <a href="#" class="btn btn-info float-right me-5" data-toggle="modal" data-target="#myModal"> 
        Filter @if(count(array_filter($_GET))>0) ({{ count(array_filter($_GET)) }}) @endif
      </a>
      @if(count(array_filter($_GET))>0)
        <a href="{{ url('/admin/contest/essays') }}" class="float-end color-red me-2"><small class="text-danger">Clear Filter</small></a>
      @endif
    </div>
   </div>

    <div class="box_general">

      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Name</th>
              <th>Attachment</th>
              <th>Author</th>
              <th>Ratings</th>
              {{-- <th>Status</th> --}}
              <th>Submited</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($essays as $essay)
              <tr>
                <td>{{$essay->title}} ({{ $essay->getCategory->name??'category not found' }})
                  @if($essay->is_winner=='yes') 
                    <span class="text-success"><i class="fa fa-trophy fa-2x"></i> Winner</span>
                  @endif
                </td>
                <td><a href="{{ asset('/public/storage/'.$essay->attachment) }}" target="_blank">View Attachment</a></td>
                <td>{{$essay->getUser->first_name ??'not found'}}</td>
                <td>{{$essay->average_rating}} ({{ $essay->get_ratings_count }})</td>
                  
                <td>{{date('d M Y, H:i',strtotime($essay->created_at))}}</td>
                <td>

                  @if( $essay->is_winner!='yes' &&
                    strtotime($essay->created_at)<strtotime(date('Y').'-'.(date('m')-1).'-1 00:00:00')
                  )
                    <a href="{{url('/admin/contest/essay/winner/'.$essay->id)}}" class="btn btn-success btn-sm" >
                      <i class="fa fa-trophy"></i> Make Winner
                    </a>
                  @endif

                  <a href="{{route('contest.view',$essay->slug)}}" target="_blank" class="btn btn-info btn-sm" ><i class="fa fa-eye"></i></a>

                </td>
              </tr>
            @empty
              <tr>
                <td collspan="6"> No essays Found</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
      {{$essays->links()}}
	  
<!-- Modal -->
  <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <form action="" method="GET" class="modal-content">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Filter</h4>
        </div>
        <div class="modal-body">
          
          <div class="form-group text-left">
            <label>Category</label>
            <select name="category" class="form-control">
              <option value="">All</option>
              @forelse ($categories as $category)
                <option value="{{ $category->slug }}" @isset($_GET['category']){{ $_GET['category']==$category->slug?'selected':'' }}@endisset>{{ $category->name }}</option>
              @empty
                
              @endforelse
            </select>
          </div>
        
          <div class="form-group text-left">
            <label class="">Search</label>
            <input class="form-control" name="search" value="@if(!empty($_GET['search'])){{ $_GET['search'] }}@endif" type="text">
          </div>
          
          <div class="form-group text-left">
            <label>Score</label>
            <select name="score" class="form-control">
              <option value="" @isset($_GET['score']){{ $_GET['score']==''?'selected':'' }}@endisset>All</option>
              <option value="1" @isset($_GET['score']){{ $_GET['score']=='1'?'selected':'' }}@endisset>1</option>
              <option value="2" @isset($_GET['score']){{ $_GET['score']=='2'?'selected':'' }}@endisset>2</option>
              <option value="3" @isset($_GET['score']){{ $_GET['score']=='3'?'selected':'' }}@endisset>3</option>
              <option value="4" @isset($_GET['score']){{ $_GET['score']=='4'?'selected':'' }}@endisset>4</option>
              <option value="5" @isset($_GET['score']){{ $_GET['score']=='5'?'selected':'' }}@endisset>5</option>
              <option value="6" @isset($_GET['score']){{ $_GET['score']=='6'?'selected':'' }}@endisset>6</option>
              <option value="7" @isset($_GET['score']){{ $_GET['score']=='7'?'selected':'' }}@endisset>7</option>
              <option value="8" @isset($_GET['score']){{ $_GET['score']=='8'?'selected':'' }}@endisset>8</option>
              <option value="9" @isset($_GET['score']){{ $_GET['score']=='9'?'selected':'' }}@endisset>9</option>
              <option value="10" @isset($_GET['score']){{ $_GET['score']=='10'?'selected':'' }}@endisset>10</option>
            </select>
          </div>
          
          <div class="form-group text-left">
            <label>Author</label>
            <select name="author" class="form-control">
              <option value="">All</option>
              @forelse ($users as $user)
                <option value="{{ $user->id }}" @isset($_GET['author']){{ $_GET['author']==$user->id?'selected':'' }}@endisset>
                  {{ $user->first_name }}
                  {{ $user->last_name }}
                </option>
              @empty
                
              @endforelse
            </select>
          </div>
          
          <div class="form-group text-left">
            <label>Year</label>
              <select name="year" class="form-control">
                @for($y=2023;$y<=date('Y');$y++)
                    <option value="{{ $y }}" @if(!empty($_GET['year'])) @if($_GET['year']==$y) selected @endif @elseif($y==date('Y')) selected @endif>{{ $y }}</option>
                @endfor
              </select>
          </div>
          
          <div class="form-group text-left">
            <label>Month</label>
            <select name="month" class="form-control">
                @for($m=1;$m<=12;$m++)
                  <option value="{{ $m }}" @if(!empty($_GET['month'])) @if($_GET['month']==$m) selected @endif @elseif($m==date('m')) selected @endif>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
          </div>

          <div class="form-group text-left">
            <label>Sort</label>
            <select name="sort" class="form-control">
              <option value="date_high_to_low"@isset($_GET['sort']){{ $_GET['sort']=='date_high_to_low'?'selected':'' }}@endisset>Upload Date(High to Low)</option>
              <option value="date_low_to_high"@isset($_GET['sort']){{ $_GET['sort']=='date_low_to_high'?'selected':'' }}@endisset>Upload Date(Low to High)</option>
              <option value="rating_high_to_low"@isset($_GET['sort']){{ $_GET['sort']=='rating_high_to_low'?'selected':'' }}@endisset>Rating(High to Low)</option>
              <option value="rating_low_to_high"@isset($_GET['sort']){{ $_GET['sort']=='rating_low_to_high'?'selected':'' }}@endisset>Rating(Low to High)</option>
            </select>
          </div>
              
      
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success" >Apply</button>
        </div>
      </form>
  
    </div>
  </div>
<!-- The Modal -->


@endsection

@section('custom-js')
   
   
@endsection
