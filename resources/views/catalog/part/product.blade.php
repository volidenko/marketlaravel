{{-- <div class="col-md-4 mb-4"> --}}
<div class="col-xs-12 col-xm-6 col-sm-6 col-md-4 col-lg-3">
    <div class="card list-item" data-target="{{ route('catalog.product', ['product' => $product->slug]) }}">
        <div class="card-body p-0 position-relative">
            <div class="position-absolute">
                @if ($product->new)
                    <span class="badge badge-info text-white ml-1">Новинка</span>
                @endif
                @if ($product->hit)
                    <span class="badge badge-danger ml-1">Лидер продаж</span>
                @endif
                @if ($product->sale)
                    <span class="badge badge-success ml-1">Распродажа</span>
                @endif
            </div>
            @if ($product->image)
                @php $url = url('storage/catalog/product/thumb/' . $product->image) @endphp
            @else
                @php $url = url('storage/catalog/product/default.jpg') @endphp
            @endif
            <img src="{{ $url }}" class="card-img-top" alt="">
        </div>
        <div class="card-body">
            <h5 class="mb-0">{{ $product->name }}</h5>
        </div>
    </div>

        <div class="card-footer">
            {{ number_format($product->price, 2, '.', '') }} грн
            <!-- Форма для добавления товара в корзину -->
            <form action="{{ route('basket.add', ['id' => $product->id]) }}" method="post"
                class="d-inline add-to-basket">
                @csrf
                <button type="submit" class="btn btn-success float-right">Купить</button>
            </form>
        </div>
    {{-- </div> --}}
</div>
