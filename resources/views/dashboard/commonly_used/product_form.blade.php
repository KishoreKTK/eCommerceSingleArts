<input type="hidden" id="current_page_type" value="{{ session()->get('login_type') }}">
@php
    $page_type = session()->get('login_type');
@endphp
<section id="basic_product_details_section">
    <p class="card-description mt-3"> Basic Details</p>
    <hr size="3">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlInput1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control"  id="exampleFormControlInput1"attributes>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="my-input">Product Display Image</label>
                    <input id="my-input" name="image" class="form-control" type="file" accept="image/*">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Select Category</label>
                    <select class="form-control" name="category_id" id="category_field_id">
                        <option value="">Please Select Category</option>
                        @foreach ($categorylist as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Short Bio (in 30 Letters)</label>
                    <input type="text" name="short_bio" value="{{ old('short_bio') }}" class="form-control"  id="exampleFormControlInput12" attributes>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Description</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" name="description" required value="{{ old('description') }}" rows="3"></textarea>
                </div>
            </div>

        </div>

        @if ($page_type == "admin")
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Select Seller</label>
                        <select class="form-control" name="seller_id" id="exampleFormControlSelect1">
                            <option value="">Please Select Seller</option>
                            @foreach ($sellerlist as $seller)
                            <option value="{{ $seller->id }}">{{ $seller->sellername }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Featured</label>
                        <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="is_featured" id="membershipRadios1" value="1"> Yes <i class="input-helper"></i></label>
                        </div>
                        <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="is_featured" id="membershipRadios2" value="0" checked> No <i class="input-helper"></i></label>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <input type="hidden" name="seller_id" value="{{ session()->get('seller_id') }}">
            <input type="hidden" name="is_featured" value="0">
        @endif

        <p class="card-description mt-3"> Additional Images</p>
        <hr size="3">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input id="banner_images" class="form-control mt-2" type="file" name="banner[]" value="" multiple accept="image/*">
                    <small id="emailHelp" class="form-text text-muted">Only Add Upto 5 Images</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="button" class="float-right btn btn-gradient-info mr-2 check_basic_details_btn">Next</button>
            </div>
        </div>
</section>

<section id="product_attributes_section">
    <p class="card-description mt-3"> Product Attributes</p>
    <hr size="3">
    <div class="row" id="product_attributes_based_on_cat">
        @include('dashboard.commonly_used.product_form_attribute_section')
    </div>

    <div class="row">
        {{-- <input type="hidden" name="product_attribute" id="product_attribute_id" value="">
        <input type="hidden" id="product_attri_check_for_stocks" value=""> --}}
        <div class="col-md-12">
            <button type="button" class="float-right btn btn-gradient-info mr-2 check_prdt_attr_btn">Next</button>
            <button type="button" class="float-right btn btn-gradient-secondary mr-2 back_to_check_basic_details_btn">Back</button>
        </div>
    </div>
</section>

<section id="product_stock_combo_section">
    <p> Product Stocks</p>
    <hr size="3">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="exampleFormControlInput1">Price</label>
                <input type="number" name="price_stock[price]" value="{{ old('price') }}" class="form-control" required id="exampleFormControlInput1"attributes>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="exampleFormControlInput1">Qunantities</label>
                <input type="text" class="form-control" name="price_stock[available_qty]"  value="{{ old('available_qty') }}" required id="exampleFormControlInput1"attributes>
            </div>
        </div>
        <input type="hidden" name="price_stock[price_type]" value="1">

        <div class="col-md-6">
            <div class="form-group">
                <label for="exampleFormControlInput531">Processing time (In Days)</label>
                <input type="number" class="form-control" name="processing_time" min="0" max="15" value="{{ old('processing_time') }}" required id="exampleFormControlInput531"attributes>
                <small class="form-text text-muted">If Out of Stock Please Enter the Time Limit Needed Recreate this Product</small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="exampleFormControlTextarea111">Shipping Details</label>
                <textarea class="form-control" id="exampleFormControlTextarea111" name="shiiping_det" required value="{{ old('shiiping_det') }}" rows="3"></textarea>
                <small class="form-text text-muted">Please Add Shipping Method.(Ex:Approx 100AED within UAE, 1000AED rest of world)</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="float-right btn btn-gradient-primary mr-2 submit_products_form">Submit</button>
            <button type="button" class="float-right btn btn-gradient-secondary mr-2 back_to_check_prdt_attr_btn">Back</button>
        </div>
    </div>
</section>
