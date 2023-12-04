<div class="leftSidebar visible-lg">
    <h5 class="subcat-title text-center">Sub-Topics</h5>
    
    @if(empty($_GET['search'] ) && !empty($categoryName))

      @if($categoryName=='random')
        <div class="list-group">
          <a href="{{ route('browse',['random','hourlydaily']) }}" class="list-group-item {{ $subcategoryName=='hourlydaily'?'active':'' }}">
            <span class="badge">{{ $totalHourlydailyWebsites }}</span>
            <!--<i class='fa fa-clock-o'></i>--> Hourly & Daily 
          </a>
          <a href="{{ route('browse',['random','hourly']) }}" class="list-group-item {{ $subcategoryName=='hourly'?'active':'' }}">
            <span class="badge">{{ $hourlySites }}</span>
            <!--<i class='fa fa-bolt'></i>--> Hourly 
          </a>
          <a href="{{ route('browse',['random','daily']) }}" class="list-group-item {{ $subcategoryName=='daily'?'active':'' }}">
            <span class="badge">{{ $dailySites }}</span>
            <!--<i class='fa fa-cogs'></i>--> Daily 
          </a>
          <a href="{{ route('browse',['random','weeklymonthly']) }}" class="list-group-item {{ $subcategoryName=='weeklymonthly'?'active':'' }}">
            <span class="badge">{{ $weeklymonthlySites }}</span>
            <!--<i class='fa fa-calendar'></i>--> Monthly 
          </a>
          <a href="{{ route('browse',['random','articles']) }}" class="list-group-item {{ $subcategoryName=='articles'?'active':'' }}">
            <span class="badge">{{ $articlesSites }}</span>
            <!--<i class='fa fa-list-alt'></i>--> Articles 
          </a>
          <a href="{{ route('browse',['random','articlespictures']) }}" class="list-group-item {{ $subcategoryName=='articlespictures'?'active':'' }}">
            <span class="badge">{{ $articlespicturesSites }}</span>
            <!--<i class='fa fa-picture-o'></i>--> Articles with pictures 
          </a>
        </div>
      @else
        <div class="list-group">
          {{-- <a href="https://eflip.com/tag/News/Random" class="list-group-item {{ $subcategoryName=='random'?'active':'' }}">
            <span class="badge">{{ $totalWebsites }}</span>
            <!--<i class="fa fa-arrows-alt"></i>-->
            Random
          </a>  --}}
          @forelse ($selectedCategory->subCategories as $subCategory)

            <a href="{{ route('browse',[$categoryName,$subCategory->name]) }}" class="list-group-item {{ $subcategoryName==$subCategory->name?'active':'' }}">
              <span class="badge">{{ App\Models\Website::where('category_id',$selectedCategory->id)->where('subcategory_id',$subCategory->id)->where('status',0)->count() }}</span>
              <!--<i class='fa fa-clock-o'></i>--> {{ $subCategory->name }}
            </a>
            
          @empty
          
          @endforelse
        </div>
      @endif

    @endif




    <div class="text-center">
      <button class="btn btn-sm suggested_btn" id="suggested_site">Suggest A Site</button>
    </div>
    <div class="text-center" style="margin-top: 10px;">
      <a style="width: 100px;" class="btn btn-sm btn-primary" href="{{ route('browse',['News','TopNews']) }}">
        <i class="fa fa-newspaper-o"></i> TopNews </a>
    </div>
    <div class="social_shares">
      <div class="text-center">
        <span style="color:#fff;">Shares</span>
        <span style="color:#777;" class="total_count"></span>
      </div>
      <ul class="list-inline">
        <li>
          <button class="btn btn-xs btn-circle share s_facebook">
            <i class="fa fa-facebook"></i>
          </button>
          <span class="counter c_facebook">0</span>
        </li>
        <li>
          <button class="btn btn-xs btn-circle share s_plus">
            <i class="fa fa-google-plus"></i>
          </button>
          <span class='counter c_plus'>0</span>
        </li>
        <li>
          <button class="btn btn-xs btn-circle share s_linkedin">
            <i class="fa fa-linkedin"></i>
          </button>
          <span class='counter c_linkedin'>9</span>
        </li>
        <li>
          <button class="btn btn-xs btn-circle share s_pinterest">
            <i class="fa fa-pinterest"></i>
          </button>
          <span class='counter c_pinterest'>0</span>
        </li>
        <li>
          <button class="btn btn-xs btn-circle share_twitter s_twitter">
            <i class="fa fa-twitter"></i>
          </button>
          <span class='counter c_twitter'>0</span>
        </li>
        <li>
          <button class="btn btn-xs btn-circle share_mail s_gmail">
            <i class="fa fa-envelope"></i>
          </button>
          <span class='counter c_gmail'>0</span>
        </li>
      </ul>
    </div>
    
  </div>