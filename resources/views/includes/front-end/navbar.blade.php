<?php 
    $categories = \App\Category::orderBy('position')->select('id', 'category')->get();
    $brands = \App\Brand::orderBy('brand')->select('id', 'brand', 'initial')->get();
    $contact = \App\Contact::first();
?>


<div class="col-md-3">
  <!-- menus and filters-->
  <div class="panel panel-default sidebar-menu">
    <div class="panel-heading cursor">
      <a href="{{ url('show/0/0/0/0/sale') }}" class="fg-black"><h3 class="panel-title">Sale </h3></a>
    </div>
  </div>
  <div class="panel panel-default sidebar-menu">
      <div class="panel-heading cursor">
          <a href="{{ url('show/0/0/0/0/paket') }}" class="fg-black"><h3 class="panel-title">Paket </h3></a>
      </div>
  </div>
  <div class="panel panel-default sidebar-menu">
    <div class="panel-heading cursor" id="more-brand" onclick="growBrand()">
      <h3 class="panel-title">Merk <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span></h3>
    </div>
    <div id="grow-brand">
      <div class="measuringWrapper-brand">
        <ul class="nav nav-pills nav-stacked category-menu">
          @foreach($brands as $brand)
            <li>
                <a href="{{ URL::to('show/' . $brand->id . '/0/0/0') }}" class="link-button col-md-10 col-xs-10 text-left">
                    {!! $brand->brand . ' (' . $brand->initial . ')'!!} 
                </a>
                <span class="badge pull-right margin-top-10">
                    {!! ' ' . $brand->availableProducts()->count() !!}
                </span>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  <div class="panel panel-default sidebar-menu">
    <div class="panel-heading cursor" id="more-category" onclick="growCategory()">
      <h3 class="panel-title">Kategori <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span></h3>
    </div>
    <div id="grow-category">
      <div class="measuringWrapper-category">
        <ul class="nav nav-pills nav-stacked category-menu">
          <div class="tree">
              @foreach($categories as $category)
              <ul class="padding-left-10 margin-bottom-0">
                  <li>
                    @if($category->availableProducts()->count() != 0)
						@if($category->subcategories)
							<span onclick="">&nbsp;>&nbsp;</span>
						@endif
                       <a href="{{ URL::to('show/0/' . $category->id . '/0/0') }}" class="category link-button"><strong class=" capital-text">{!! $category->category !!}</strong></a>
                      <span class="badge pull-right">
                          {!! ' ' . $category->availableProducts()->count() !!}
                      </span>
                      <ul class="padding-left-20 subtree">
                          <li>
                              @foreach($category->subcategories as $subcategory)
                                <a href="{{ URL::to('show/0/0/' . $subcategory->id . '/0') }}" class="padding-left-10 margin-right-min-20">
                                  {!! $subcategory->subcategory !!}
                                  <span class="badge pull-right">{!! ' ' . $subcategory->availableProducts()->count() !!}</span>
                                </a>
                                
                              @endforeach
                          </li>
                      </ul>
                    @else
                      <span onclick="">&nbsp;&nbsp;&nbsp;&nbsp; <a href="{{ URL::to('show/0/' . $category->id . '/0/0') }}" class="category link-button not-active"><strong class="capital-text">{!! $category->category !!}</strong></a>
                      <span class="badge pull-right">
                          {!! ' ' . $category->availableProducts()->count() !!}
                      </span>
                    @endif
                  </li>
              </ul>
              @endforeach
          </div>
        </ul>
      </div>
    </div>
  </div>


  
  @if($contact)
  <div class="panel panel-default sidebar-menu">
    <div class="panel-heading cursor" id="more-brand">
      <h3 class="panel-title">Info</h3>
    </div>
    <div class="panel-body">
      @if($contact != null)
      {!! $contact->info !!}
      @endif
    </div>
  </div>
  @endif
  
</div>


  @section('add_script')
  <script type="text/javascript" src="{{ URL::asset('ext/js/custom/navbar.js') }}"></script>
  @endsection
