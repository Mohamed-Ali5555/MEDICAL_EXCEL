@php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))

@if (\Request::is('products*') )
    {{-- this code belongs to search and under belongs to filter seller --}}
    @foreach ($products as $product)
        @if ($product->children != null)
            @foreach ($product->children as $product1)
                @if ($product->id == $product1->parent_id)
                    @if (!empty($product['product_id']))
                        @php($product = $product->product)
                    @endif


                    <div
                        class=" {{ Request::is('products*') ? 'col-lg-3 col-md-4 col-sm-4 col-6' : 'col-lg-2 col-md-3 col-sm-4 col-6' }} {{ Request::is('shopView*') ? 'col-lg-3 col-md-4 col-sm-4 col-6' : '' }} mb-2 p-2">
                        @if (!empty($product1))
                            @include('web-views.partials._filter-single-product', [
                                'product1' => $product1,
                                'decimal_point_settings' => $decimal_point_settings,
                            ])
                        @endif
                    </div>
                @endif
            @endforeach
        @else
            <div>not added</div>
        @endif
    @endforeach
@else

    @foreach ($products as $product)
        <div
            class=" {{ Request::is('shopView*') ? 'col-lg-3 col-md-4 col-sm-4 col-6' : '' }} mb-2 p-2"> 
            @if (!empty($product))
                @include('web-views.partials._filter-seller', [
                    'product' => $product,
                    'decimal_point_settings' => $decimal_point_settings,
                ])
            @endif
        </div>
    @endforeach

@endif
<div class="col-12">
    <nav class="d-flex justify-content-between pt-2" aria-label="Page navigation" id="paginator-ajax">
        {{-- {!! $products->links() !!} --}}
    </nav>
</div>



