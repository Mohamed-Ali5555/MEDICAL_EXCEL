@extends('layouts.front-end.app')

@section('title', $product['name'])



@section('content')
    <div class="__inline-23">
        <!-- Page Content-->
        <div class="container mt-4 rtl" style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="row text-right mb-3" dir="rtl">
                <div class="col-lg-3">
                    <div class="product-details-shipping-details">
                        <div class="shipping-details-bottom-border">
                            <div class="px-3 py-3">
                                <img class="{{ Session::get('direction') === 'rtl' ? 'float-right ml-2' : 'mr-2' }} __img-20"
                                    src="{{ asset('public/assets/front-end/png/Payment.png') }}" alt="">
                                <span>{{ \App\CPU\translate('Safe Payment') }}</span>
                            </div>
                        </div>
                        <div class="shipping-details-bottom-border">
                            <div class="px-3 py-3">
                                <img class="{{ Session::get('direction') === 'rtl' ? 'float-right ml-2' : 'mr-2' }} __img-20"
                                    src="{{ asset('public/assets/front-end/png/money.png') }}" alt="">
                                <span>{{ \App\CPU\translate('7 Days Return Policy') }}</span>
                            </div>
                        </div>
                        <div class="shipping-details-bottom-border">
                            <div class="px-3 py-3">
                                <img class="{{ Session::get('direction') === 'rtl' ? 'float-right ml-2' : 'mr-2' }} __img-20"
                                    src="{{ asset('public/assets/front-end/png/Genuine.png') }}" alt="">
                                <span>{{ \App\CPU\translate('100% Authentic Products') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-5 col-12">
                      <h1>{{ $product->name }}</h1>
                        @if($product->brand !=null)
                                <p class="mb-2 __inline-24">{{ $product->brand->name }}</p>
                                  @endif
                    <img onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'"
                        src="{{ asset("storage/app/public/product/thumbnail/$product->thumbnail") }}" alt="Product thumb">
                </div>
            </div>

 
            @foreach ($sellers_products  as $key =>  $product)

                {{-- @foreach ($product->children ) --}}

                    <div class="row text-right" dir="rtl">
                        <div class="col-3"></div>
                        <div class="col-6">
                            <div class="details __h-100">
                                <span class="mb-2 __inline-24">{{ $product->seller->shop->name }}</span>

                                <div class="mb-3 mt-1 d-flex">


                             <div class="" style="background-color: {{ $key < 1 ? '#3c6' : '#1d46ed' }};
                                         border-radius: 4px;padding:5px;">
                                    <span class="for-discoutn-value1 p-1 pl-2 pr-2 pt-1 ">
                                        @if ($product->discount_type == 'percent')
                                            {{ round($product->discount, !empty($decimal_point_settings) ? $decimal_point_settings : 0) }}%
                                        @elseif($product->discount_type == 'flat')
                                            {{ \App\CPU\Helpers::currency_converter($product->discount) }}
                                        @endif
                                        {{ \App\CPU\translate('off') }}
                                    </span>
                             </div>

                                    @if ($product->discount > 0)
                                        <strike style="color: #E96A6A;"
                                            class="{{ Session::get('direction') === 'rtl' ? 'ml-1' : 'mr-3' }}">
                                            {{ \App\CPU\Helpers::currency_converter($product->unit_price) }}
                                        </strike>
                                    @endif
                                    <span class="h3 font-weight-normal text-accent ">
                                        {{ \App\CPU\Helpers::get_price_range($product) }}
                                    </span>
                                    <span
                                        class="{{ Session::get('direction') === 'rtl' ? 'mr-2' : 'ml-2' }} __text-12px font-regular">
                             
                                    </span>
                                </div>

                                <form id="add-to-cart-form" class="mb-2">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                    <div
                                        class="position-relative {{ Session::get('direction') === 'rtl' ? 'ml-n4' : 'mr-n4' }} mb-2">
                                        @if (count(json_decode($product->colors)) > 0)
                                            <div class="flex-start">
                                                <div class="product-description-label mt-2 text-body">
                                                    {{ \App\CPU\translate('color') }}:
                                                </div>
                                                <div>
                                                    <ul class="list-inline checkbox-color mb-1 flex-start {{ Session::get('direction') === 'rtl' ? 'mr-2' : 'ml-2' }}"
                                                        style="padding-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 0;">
                                                        @foreach (json_decode($product->colors) as $key => $color)
                                                            <div>
                                                                <li>
                                                                    <input type="radio"
                                                                        id="{{ $product->id }}-color-{{ str_replace('#', '', $color) }}"
                                                                        name="color" value="{{ $color }}"
                                                                        @if ($key == 0) checked @endif>
                                                                    <label style="background: {{ $color }};"
                                                                        for="{{ $product->id }}-color-{{ str_replace('#', '', $color) }}"
                                                                        data-toggle="tooltip"
                                                                        onclick="focus_preview_image_by_color('{{ str_replace('#', '', $color) }}')">
                                                                        <span class="outline"></span></label>
                                                                </li>
                                                            </div>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                        @php
                                            $qty = 0;
                                            if (!empty($product->variation)) {
                                                foreach (json_decode($product->variation) as $key => $variation) {
                                                    $qty += $variation->qty;
                                                }
                                            }
                                        @endphp
                                    </div>
                                    @foreach (json_decode($product->choice_options) as $key => $choice)
                                        <div class="row flex-start mx-0">
                                            <div
                                                class="product-description-label text-body mt-2 {{ Session::get('direction') === 'rtl' ? 'pl-2' : 'pr-2' }}">
                                                {{ $choice->title }}
                                                :
                                            </div>
                                            <div>
                                                <ul class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2 mx-1 flex-start row"
                                                    style="padding-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 0;">
                                                    @foreach ($choice->options as $key => $option)
                                                        <div>
                                                            <li class="for-mobile-capacity">
                                                                <input type="radio"
                                                                    id="{{ $choice->name }}-{{ $option }}"
                                                                    name="{{ $choice->name }}" value="{{ $option }}"
                                                                    @if ($key == 0) checked @endif>
                                                                <label class="__text-12px"
                                                                    for="{{ $choice->name }}-{{ $option }}">{{ $option }}</label>
                                                            </li>
                                                        </div>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Quantity + Add to cart -->
                                    <div class="mt-2">
                                        <div class="product-quantity d-flex flex-wrap align-items-center __gap-15">
                                            <div class="d-flex align-items-center">
                                                <div class="product-description-label text-body mt-2">
                                                    {{ \App\CPU\translate('Quantity') }}:</div>
                                                <div class="d-flex justify-content-center align-items-center __w-160px"
                                                    style="color: {{ $web_config['primary_color'] }}">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-number __p-10" type="button"
                                                            data-type="minus" data-field="quantity" disabled="disabled"
                                                            style="color: {{ $web_config['primary_color'] }}">
                                                            -
                                                        </button>
                                                    </span>
                                                    <input type="text" name="quantity"
                                                        class="form-control input-number text-center cart-qty-field __inline-29"
                                                        placeholder="1" value="{{ $product->minimum_order_qty ?? 1 }}"
                                                        product-type="{{ $product->product_type }}"
                                                        min="{{ $product->minimum_order_qty ?? 1 }}" max="100">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-number __p-10" type="button"
                                                            product-type="{{ $product->product_type }}" data-type="plus"
                                                            data-field="quantity"
                                                            style="color: {{ $web_config['primary_color'] }}">
                                                            +
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div id="chosen_price_div">
                                                <div
                                                    class="d-flex justify-content-center align-items-center {{ Session::get('direction') === 'rtl' ? 'ml-2' : 'mr-2' }}">
                                                    <div class="product-description-label">
                                                        <strong>{{ \App\CPU\translate('total_price') }}</strong> : </div>
                                                    &nbsp; <strong id="chosen_price"></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row no-gutters d-none mt-2 flex-start d-flex">
                                        <div class="col-12">
                                            @if ($product['product_type'] == 'physical' && $product['current_stock'] <= 0)
                                                <h5 class="mt-3 text-danger">{{ \App\CPU\translate('out_of_stock') }}</h5>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="__btn-grp mt-2 mb-3">
                                        @if (
                                            ($product->added_by == 'seller' &&
                                                ($seller_temporary_close ||
                                                    (isset($product->seller->shop) &&
                                                        $product->seller->shop->vacation_status &&
                                                        $current_date >= $seller_vacation_start_date &&
                                                        $current_date <= $seller_vacation_end_date))) ||
                                                ($product->added_by == 'admin' &&
                                                    ($inhouse_temporary_close ||
                                                        ($inhouse_vacation_status &&
                                                            $current_date >= $inhouse_vacation_start_date &&
                                                            $current_date <= $inhouse_vacation_end_date))))
                                            <button class="btn btn-block btn--primary string-limit" type="button"
                                                disabled>
                                                {{ \App\CPU\translate('add_to_cart') }}
                                            </button>
                                        @else
                                            <button
                                                class="btn btn-block btn--primary element-center btn-gap-{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}"
                                                onclick="addToCart()" type="button">
                                                <span class="string-limit">{{ \App\CPU\translate('add_to_cart') }}</span>
                                            </button>
                                        @endif
                                        @if (
                                            ($product->added_by == 'seller' &&
                                                ($seller_temporary_close ||
                                                    (isset($product->seller->shop) &&
                                                        $product->seller->shop->vacation_status &&
                                                        $current_date >= $seller_vacation_start_date &&
                                                        $current_date <= $seller_vacation_end_date))) ||
                                                ($product->added_by == 'admin' &&
                                                    ($inhouse_temporary_close ||
                                                        ($inhouse_vacation_status &&
                                                            $current_date >= $inhouse_vacation_start_date &&
                                                            $current_date <= $inhouse_vacation_end_date))))
                                            <div class="alert alert-danger" role="alert">
                                                {{ \App\CPU\translate('this_shop_is_temporary_closed_or_on_vacation._You_cannot_add_product_to_cart_from_this_shop_for_now') }}
                                            </div>
                                        @endif
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>

                    
                {{-- @endforeach --}}
            @endforeach
        </div>
        <div class="modal fade rtl" id="show-modal-view" tabindex="-1" role="dialog"
            aria-labelledby="show-modal-image" aria-hidden="true"
            style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body flex justify-content-center">
                        <button class="btn btn-default __inline-33"
                            style="{{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}: -7px;"
                            data-dismiss="modal">
                            <i class="fa fa-close"></i>
                        </button>
                        <img class="element-center" id="attachment-view" src="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        cartQuantityInitialize();
        getVariantPrice();
        $('#add-to-cart-form input').on('change', function() {
            getVariantPrice();
        });

        function showInstaImage(link) {
            $("#attachment-view").attr("src", link);
            $('#show-modal-view').modal('toggle')
        }

        function focus_preview_image_by_color(key) {
            $('a[href="#image' + key + '"]')[0].click();
        }
    </script>
    <script>
        $(document).ready(function() {
            load_review();
        });
        let load_review_count = 1;

        function load_review() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: '{{ route('review-list-product') }}',
                data: {
                    product_id: {{ $product->id }},
                    offset: load_review_count
                },
                success: function(data) {
                    $('#product-review-list').append(data.productReview)
                    if (data.not_empty == 0 && load_review_count > 2) {
                        toastr.info('{{ \App\CPU\translate('no more review remain to load') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        console.log('iff');
                    }
                }
            });
            load_review_count++
        }
    </script>

    {{-- Messaging with shop seller --}}
    <script>
        $('#contact-seller').on('click', function(e) {
            // $('#seller_details').css('height', '200px');
            $('#seller_details').animate({
                'height': '276px'
            });
            $('#msg-option').css('display', 'block');
        });
        $('#sendBtn').on('click', function(e) {
            e.preventDefault();
            let msgValue = $('#msg-option').find('textarea').val();
            let data = {
                message: msgValue,
                shop_id: $('#msg-option').find('textarea').attr('shop-id'),
                seller_id: $('.msg-option').find('.seller_id').attr('seller-id'),
            }
            if (msgValue != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: '{{ route('messages_store') }}',
                    data: data,
                    success: function(respons) {
                        console.log('send successfully');
                    }
                });
                $('#chatInputBox').val('');
                $('#msg-option').css('display', 'none');
                $('#contact-seller').find('.contact').attr('disabled', '');
                $('#seller_details').animate({
                    'height': '125px'
                });
                $('#go_to_chatbox').css('display', 'block');
            } else {
                console.log('say something');
            }
        });
        $('#cancelBtn').on('click', function(e) {
            e.preventDefault();
            $('#seller_details').animate({
                'height': '114px'
            });
            $('#msg-option').css('display', 'none');
        });
    </script>

    <script type="text/javascript"
        src="https://platform-api.sharethis.com/js/sharethis.js#property=5f55f75bde227f0012147049&product=sticky-share-buttons"
        async="async"></script>
@endpush
