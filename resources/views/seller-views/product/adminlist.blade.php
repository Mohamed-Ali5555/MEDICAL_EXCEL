@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('Product List'))

@push('css_or_js')
    {{-- <link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" /> --}}

@endpush

@section('content')







    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/back-end/img/products.png') }}" alt="">
                {{ \App\CPU\translate('Products') }}
                {{-- <span class="badge badge-soft-dark radius-50 fz-14 ml-1">{{ $products->total() }}</span> --}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">


                            <div class="col-lg-4">
                                <form action="{{ url()->current() }}" method="GET">
                                    <!-- Search -->
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input type="search" id="searchInput" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search by Product Name') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit"
                                            class="btn btn--primary">{{ \App\CPU\translate('search') }}</button>
                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>

                            {{-- ظظظظظظظ --}}
                          

                                <div class="col-sm-4">
                                    <div class="input-group d-flex justify-content-end" >
                                        <select name="category" id="category" class="form-control js-select2-custom w-100" title="select category" onchange="set_category_filter(this.value)">
                                            <option value="">{{\App\CPU\translate('All Categories')}}</option>
                                            @foreach ($categories as $item)
                                            <option value="{{$item->id}}" {{$category==$item->id?'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                            {{-- ظظظظظظظظ --}}

                     





                            <div class="col-lg-4 mt-3 mt-lg-0 d-flex flex-wrap gap-3 justify-content-lg-end">
                                <div>
                                    <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        {{ \App\CPU\translate('export') }}
                                        <i class="tio-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a class="dropdown-item"
                                                href="{{ route('seller.product.bulk-export') }}">{{ \App\CPU\translate('excel') }}</a>
                                        </li>
                                        <div class="dropdown-divider"></div>
                                    </ul>
                                </div>
                                {{-- <a href="{{route('seller.product.stock-limit-list',['in_house', ''])}}" class="btn btn-info">
                                    <i class="tio-add-circle"></i>
                                    <span class="text">{{\App\CPU\translate('Limited_Stocks')}}</span>
                                </a> --}}
                                {{-- <a href="{{route('seller.product.add-new')}}" class="btn btn--primary">
                                    <i class="tio-add"></i>
                                    <span class="text">{{\App\CPU\translate('Add new product')}}</span>
                                </a> --}}
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                            style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ \App\CPU\translate('SL') }}</th>
                                    <th>{{ \App\CPU\translate('Product Name') }}</th>
                                    <th>{{ \App\CPU\translate('Product Type') }}</th>
                                    <th>{{ \App\CPU\translate('purchase_price') }}</th>
                                    <th>{{ \App\CPU\translate('selling_price') }}</th>
                                    <th class="text-center __w-5px">{{ \App\CPU\translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="productList">
                                @foreach ($products as $k => $p)
                                    <tr class="product-item">
                                        <th scope="row">{{ $products->firstitem() + $k }}</th>
                                        <td>
                                            <a href="{{ route('seller.product.view', [$p['id']]) }}"
                                                class="media align-items-center gap-2 w-max-content">
                                                <img src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $p['thumbnail'] }}"
                                                    onerror="this.src='{{ asset('/public/assets/back-end/img/brand-logo.png') }}'"class="avatar border"
                                                    alt="">
                                                <span class="media-body title-color hover-c1">
                                                    {{ \Illuminate\Support\Str::limit($p['name'], 30) }}
                                                </span>
                                            </a>
                                        </td>
                                        <td>{{ ucfirst($p['product_type']) }}</td>
                                        <td>
                                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['purchase_price'])) }}
                                        </td>
                                        <td>
                                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['unit_price'])) }}
                                        </td>

                                        <td>
                                            <div class="d-flex gap-10 select-product">
                                                {{-- @if ($p['id'] == $p['parent_id'] && $p['user_id'] == auth('seller')->id()) --}}
                                                <button id="{{ $p['id'] }}"
                                                    class="btn btn-outline-success duplicate1" title="أضف إلي متجرى"
                                                    data-url="{{ route('seller.product.duplicate', $p['id']) }}">
                                                    <i class="tio-add"></i>
                                                    إضافه إلى متجرى
                                                </button>

                                                {{-- @else
                                                 <div>its added</div>
                                                @endif --}}

                                            </div>
                                            {{-- <form action="{{route('seller.product.duplicate',[$p['id']])}}"
                                                method="post" id="product-{{$p['id']}}">
                                            @csrf @method('post')
                                        </form> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div id="AjaxResult"></div>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{ $products->links() }}
                        </div>
                    </div>

                    @if (count($products) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160"
                                src="{{ asset('public/assets/back-end') }}/svg/illustrations/sorry.svg"
                                alt="Image Description">
                            <p class="mb-0">{{ \App\CPU\translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- ////////////// --}}
    {{-- // auto complete  --}}
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    {{-- <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script> --}}
    {{-- /// end this  --}}
    <script>
        $(document).ready(function() {
            $('#searchInput').on('input', function() {
                var searchTerm = $(this).val();
                $.ajax({
                    url: "{{ route('seller.product.autoSearch') }}",
                    method: 'GET',
                    data: {
                        term: searchTerm
                    },
                    success: function(response) {
                        var productList = $('#productList');
                        productList.empty();

                        if (response.products.length > 0) {

                            var counter = 1;

                            $.each(response.products, function(index, product) {



                                // var unit_price =
                                //   '{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency(' + product.unit_price + ')) }}';




                                var productItem = $('<tr>').addClass('product-item');




                                var selectProductDiv = $('<div>').addClass(
                                    'd-flex gap-10 select-product ');

                                var duplicateButton = $('<button>').attr('id', product
                                        .id)
                                    .addClass('btn btn-outline-success duplicate12')
                                    .attr('title', 'أضف إلي متجرى')
                                    .attr('data-url',
                                        "{{ route('seller.product.duplicate', ['id' => '']) }}"
                                    )

                                    .html('<i class="tio-add"></i> إضافه إلى متجرى');

                                selectProductDiv.append(duplicateButton);





                                productItem.append($('<td>').text(counter));




                                // productItem.append($('<img>').attr('src',
                                //  '{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/' +
                                //  product.thumbnail).addClass(
                                //   'avatar border'));
                                // productItem.append($('<td>').text(product.name));
                                productItem.append($('<td>').append(
                                    $('<div>').addClass('product-info').append(
                                        $('<img>').attr('src',
                                            '{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/' +
                                            product.thumbnail).addClass(
                                            'avatar border'),
                                        $('<span>').text(product.name)
                                    )
                                ));

                                productItem.append($('<td>').text(product
                                    .product_type));


                                productItem.append($('<td>').text(product
                                    .purchase_price + 'EGP'));

                                productItem.append($('<td>').text(product.unit_price +
                                    'EGP'));

                                productItem.append($('<td>').append(selectProductDiv));





                                productList.append(productItem);

                                counter++;
                            });
                        } else {
                            productList.append($('<p>').text('No products found.'));
                        }
                    }
                });
            });
        });
    </script>



    {{-- ///////////////// --}}






    <!-- Page level plugins -->
    <script src="{{ asset('public/assets/back-end') }}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('public/assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        //  $(document).ready(function() {
        //      $(document).on('click', '.duplicate1,.duplicate12', function(event) {


        $(document).ready(function() {
            $(document).off('click', '.duplicate1,.duplicate12').on('click', '.duplicate1,.duplicate12', function(
                event) {



                event.preventDefault();
                var id = $(this).attr("id");
                //console.log(id);
                var url = $(this).data('url');
                //   alert(url);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        $("#AjaxResult").html(data);
                    },
                })
            });
        });


        // Call the dataTables jQuery plugin
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });

        $('.status').on('change', function() {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            let t = $(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('seller.product.status-update') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data.success == true) {
                        toastr.success('{{ \App\CPU\translate('Status updated successfully') }}');
                    } else if (data.success == false) {
                        t.removeAttr('checked');
                        toastr.error(
                            '{{ \App\CPU\translate('Status updated failed. Product must be approved') }}'
                        );
                    }
                }
            });
        });
    </script>



    <script>
        $("#search-brand").on("keyup", function() {
            var value = this.value.toLowerCase().trim();
            $("#lista1 div>label").show().filter(function() {
                return $(this).text().toLowerCase().trim().indexOf(value) == -1;
            }).hide();
        });
    </script>










<!-- JS Plugins Init. -->
<script>
    jQuery(".search-bar-input").on('keyup', function () {
        //$('#search-box').removeClass('d-none');
        $(".search-card").removeClass('d-none').show();
        let name = $(".search-bar-input").val();
        if (name.length > 0) {
            $('#search-box').removeClass('d-none').show();
            $.get({
                url: '{{route('seller.pos.search-products')}}',
                dataType: 'json',
                data: {
                    name: name
                },
                beforeSend: function () {
                    $('#loading').removeClass('d-none');
                },
                success: function (data) {
                    //console.log(data.count);

                    $('.search-result-box').empty().html(data.result);
                    if (data.count == 1) {
                        $('.search-result-box').empty().hide();
                        $('#search').val('');
                        quickView(data.id);
                    }

                },
                complete: function () {
                    $('#loading').addClass('d-none');
                },
            });
        } else {
            $('.search-result-box').empty();
        }
    });
</script>




<script>



    function set_category_filter(id) {
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('category_id', id);
        location.href = nurl;
    }


    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        var keyword = $('#datatableSearch').val();
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('keyword', keyword);
        location.href = nurl;
    });

 
    




  
    $(function () {
        $(document).on('click', 'input[type=number]', function () {
            this.select();
        });
    });



</script>

@endpush
