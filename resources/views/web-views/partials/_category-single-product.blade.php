@php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews))


<div class="product-single-hover" >
    <div class="overflow-hidden position-relative">
        <div class=" inline_product clickable d-flex justify-content-center"
                style="background:{{$web_config['primary_color']}}10;">
            @if($product1->discount > 0)
                <div class="d-flex">
                        <span class="for-discoutn-value p-1 pl-2 pr-2">
                        @if ($product1->discount_type == 'percent')
                                {{round($product1->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                            @elseif($product1->discount_type =='flat')
                                {{\App\CPU\Helpers::currency_converter($product1->discount)}}
                            @endif
                            {{\App\CPU\translate('off')}}
                        </span>
                </div>
            @else
                <div class="d-flex justify-content-end for-dicount-div-null">
                    <span class="for-discoutn-value-null"></span>
                </div>
            @endif
            <div class="d-flex d-block">
                <a href="{{route('product',$product->slug)}}" class="d-block">
                    <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'">
                </a>
            </div>
        </div>
        <div class="single-product-details">
            <div class="text-center">
                <a href="{{route('product',$product->slug)}}" style="font-weight: 400;
                    font-size: 13px; ">
                    {{ Str::limit($product['name'], 18) }}
                </a>
            </div> 
            <div class="justify-content-between text-center">
                <div class="product-price text-center" style="font-weight: 400;
                font-size: 12px;">
                    @if($product1->discount > 0)
                        <strike style="font-size: 12px!important;color: #E96A6A!important;">
                            {{\App\CPU\Helpers::currency_converter($product1->unit_price)}}
                        </strike><br>
                    @endif
                    <span class="text-accent">
                        {{\App\CPU\Helpers::currency_converter(
                            $product1->unit_price-(\App\CPU\Helpers::get_product_discount($product1,$product1->unit_price))
                        )}}
                    </span>
                </div>
            </div>

        </div>
        <div class="text-center quick-view" >
            @if(Request::is('product/*'))
                <a class="btn btn--primary btn-sm" href="{{route('product',$product->slug)}}">
                    <i class="czi-forward align-middle {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                    {{\App\CPU\translate('View')}}
                </a>
            @else
                <a class="btn btn--primary btn-sm"
                style="margin-top:0px;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;" href="javascript:"
                onclick="quickView('{{$product1->id}}')">
                    <i class="czi-eye align-middle {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                    {{\App\CPU\translate('Quick')}}   {{\App\CPU\translate('View')}}
                </a>
            @endif
        </div>
    </div>
</div>


