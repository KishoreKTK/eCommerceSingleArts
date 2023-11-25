<div class="card">
    <div class="card-body">
        {{-- <h4 class="card-title">Seller Detail</h4> --}}
        <div class="d-flex flex-column align-items-center text-center">
            <img src="{{ asset($seller_det->sellerprofile) }}" alt="Seller"
                class="rounded-circle p-1 bg-primary" width="150">
            <div class="mt-3">
                <h4>{{ $seller_det->sellername }}</h4>
                <p class="text-secondary mb-1">{{ $seller_det->selleremail  }}</p>
                <p class="text-secondary mb-1">
                    @if(!is_null($seller_det->mobile))
                    <i class="mdi mdi-cellphone-android" data-toggle="tooltip" data-placement="left" title="Mobile"></i>&nbsp;{{ $seller_det->mobile  }}
                    @endif
                </p>
                <p class="text-muted font-size-sm">
                    @if(!is_null($seller_det->sellerabout))
                        {{ $seller_det->sellerabout }}
                    @endif
                </p>
            </div>
        </div>
        <hr class="my-4">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap"
                data-toggle="tooltip" data-placement="top" title="Business Category">
                <h6 class="mb-0">
                    <i class="mdi mdi-lan" aria-hidden="true"></i>
                </h6>
                <span class="text-secondary">
                    {{ $seller_det->seller_buss_type }}
                </span>
            </li>

            @if(!is_null($seller_det->seller_full_name_buss))
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap"
                data-toggle="tooltip" data-placement="top" title="Business Name">
                <h6 class="mb-0">
                    <i class="mdi mdi-briefcase" aria-hidden="true"></i>
                </h6>
                <span class="text-secondary">
                    {{ $seller_det->seller_full_name_buss }}
                </span>
            </li>
            @endif

            @if(!is_null($seller_det->seller_trade_exp_dt))
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap"
                data-toggle="tooltip" data-placement="top" title="Trade License Expiry Date">
                <h6 class="mb-0">
                    <i class="mdi mdi-calendar-clock" aria-hidden="true"></i>
                </h6>
                <span class="text-secondary">
                    {{ $seller_det->seller_trade_exp_dt }}
                </span>
            </li>
            @endif


            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap"
                data-toggle="tooltip" data-platcement="top" title="Seller Approved Date">
                <h6 class="mb-0">
                    <i class="mdi mdi-calendar-today" aria-hidden="true"></i>
                </h6>
                <span class="text-secondary">
                    {{ $seller_det->created_at }}
                </span>
            </li>
        </ul>

        @if(Route::current()->getName() == 'admin.SellerDetail')
            @if($seller_det->seller_trade_license != null)
                @if($seller_det->seller_buss_type == "Business")
                <div class="mt-2 d-flex justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-block view_trade_license"
                    data-seller_name="{{ $seller_det->sellername }}"
                    data-trade_licenseurl='{{ asset($seller_det->seller_trade_license) }}'>View License</button>
                </div>
                @endif
            @endif
        @endif
    </div>
</div>
