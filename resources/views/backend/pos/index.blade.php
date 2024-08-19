@extends('admin.pos_master')
@section('content')
@section('title', 'Pos')

<style type="text/css">
    @import url(https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css);

    .request__product {
        position: absolute;
        top: 100%;
        left: 1px;
        background: #e6e6e7;
        width: 100%;
        height: auto;
        z-index: 9;
    }

    .request__product ul li div {
        font-size: 16px;
        cursor: pointer;
        line-height: 16px;
        padding: 7px 13px;
    }

    .request__product ul li div:hover {
        background: aliceblue;
    }

    .request__product_show {
        position: absolute;
        top: 11%;
        left: 13px;
        background: #e6e6e7;
        width: 28% !important;
        height: auto;
        z-index: 9;
        border-radius: 4px;
    }

    .request__product_show ul li div {
        font-size: 16px;
        cursor: pointer;
        line-height: 16px;
        padding: 7px 13px;
    }

    .request__product_show ul li div:hover {
        background: aliceblue;
    }

    .collapse__div label {
        margin: 0px !important;
    }

    .col-md-12.addRow {
        padding: 10px;
        border-radius: 6px;
    }

    .select2-container .select2-selection--single {
        display: flex;
        height: 38px;
        align-items: center;
        border: none;
        border-radius: 0;
        border-left: 1px solid #ddd;
    }

    .card_box_header .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 8px;
    }

    .select2-container .select2-selection--single {
        border-left: none
    }

    .transection_show {
        position: absolute;
        top: 82%;
        background: #e6e6e7;
        width: 22%;
        height: auto;
        z-index: 9;
        left: 52%
    }

    .transection_show ul li div {
        font-size: 16px;
        cursor: pointer;
        line-height: 16px;
        padding: 7px 13px;
    }

    .transection_show ul li div:hover {
        background: aliceblue;
    }

    .transection__section {
        position: relative;
    }

    .transection_number_show {
        left: 0;
        top: 100%;
        width: 100%;
    }

    .quantityChange {
        padding: 6px 10px;
    }

    .quantity__number {
        max-width: 30px;
        width: 100%;
    }

    @media (min-width: 1501px) and (max-width: 1800px) {
        .quantityChange {
            padding: 3px 6px !important;
        }
    }

    /* Normal desktop :1200px. */
    @media (min-width: 1200px) and (max-width: 1500px) {
        .quantityChange {
            padding: 3px 4px !important;
        }

        .quantity__number {
            max-width: 20px;
        }
    }


    /* Normal desktop :992px. */
    @media (min-width: 992px) and (max-width: 1200px) {
        .quantityChange {
            padding: 3px;
        }

        .quantity__number {
            max-width: 25px;
        }
    }

    /* Tablet desktop :768px. */
    @media (min-width: 768px) and (max-width: 991px) {
        .quantity__btn__change {
            justify-content: center;
        }
    }

    /* Large Mobile :480px. */
    @media only screen and (min-width: 480px) and (max-width: 767px) {
        .quantityChange {
            padding: 3px !important;
        }

        .quantity__btn__change {
            justify-content: center;
        }
    }

    @media all and (max-width: 480px) {
        .quantityChange {
            padding: 5px !important;
        }
    }

    @media all and (max-width: 320px) {
        .quantityChange {
            padding: 5px !important;
        }
    }

    #addcustomer_modal span.select2.select2-container.select2-container--default {
        border: 1px solid #ddd !important;
    }
</style>

<!-- pos header -->
<header class="pos_header">
    <!-- container -->
    <div class="container-fluid">
        <!-- row -->
        <div class="row gy-3 align-items-center">
            <!-- header left -->
            <div class="col-sm-6 col-8">
                <div class="header_right d-flex gap-2 align-items-center">
                    <!-- get date -->
                    <div class="get_date"> @php echo date("Y/m/d |") @endphp <span id="clock"></span></div>
                </div>
            </div>
            <!-- header right -->
            <div class="col-sm-6 col-4">
                <!-- all button -->
                <div class="header_btnwrapper">
                    <div class="d-flex align-items-stretch justify-content-end gap-2">
                        <!--  fullsecreen button -->
                        <a href="{{ route('admin.dashboard') }}" target="_blank">
                            <button type="button" class="btn_main header_btn bg-warning">
                                <span>Dashboard</span>
                            </button>
                        </a>
                        <button type="button" title="Click Fullsecreen" class="btn_main header_btn bg-primary"
                            id="fullsecreen_btn">
                            <span><i class="fa-solid fa-expand"></i></span>
                        </button>

                        <!-- calculator -->
                        <div class="position-relative">
                            <!--  calculator button -->
                            <button type="button" title="Calculator" class="btn_main header_btn bg-info"
                                id="calculator_btn">
                                <span><i class="fa-solid fa-calculator"></i></span>
                            </button>
                            <!-- calculator -->
                            <div class="calculator" id="calculator">
                                <div class="display">
                                    <span id="current-calc"></span>
                                    <span id="result">0</span>
                                </div>
                                <div class="cal_row">
                                    <span data-key="Backspace" id="other">&#8592;</span>
                                    <span data-key="?" id="other">&plusmn;</span>
                                    <span data-key="%" id="operator">&percnt;</span>
                                    <span data-key="/" id="operator">&divide;</span>
                                </div>
                                <div class="cal_row">
                                    <span data-key="7" id="num">7</span>
                                    <span data-key="8" id="num">8</span>
                                    <span data-key="9" id="num">9</span>
                                    <span data-key="*" id="operator">&times;</span>
                                </div>
                                <div class="cal_row">
                                    <span data-key="4" id="num">4</span>
                                    <span data-key="5" id="num">5</span>
                                    <span data-key="6" id="num">6</span>
                                    <span data-key="-" id="operator">&minus;</span>
                                </div>
                                <div class="cal_row">
                                    <span data-key="1" id="num">1</span>
                                    <span data-key="2" id="num">2</span>
                                    <span data-key="3" id="num">3</span>
                                    <span data-key="+" id="operator">&plus;</span>
                                </div>
                                <div class="cal_row">
                                    <span data-key="Delete" id="del">CE</span>
                                    <span data-key="0" id="num">0</span>
                                    <span data-key="." id="other">.</span>
                                    <span data-key="Enter" id="equ">&equals;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- pos header end -->

<!-- content main -->
<div class="content_main py-4">
    <!-- container -->
    <div class="container-fluid">
        <div class="row flex-row-reverse g-2">
            <!-- cart column -->
            <div class="col-xl-5 col-lg-6">
                <!-- cart box -->
                <div class="cart__box">
                    <!-- card box header -->
                    <div class="card_box_header mb-3">
                        <div class="row g-2">
                            <div class="col-md-6 col-lg-6">
                                <!-- select customer -->
                                <div class="input-group najmul__input__group">
                                    <span class="input-group-text rounded-0"><i class="fa-solid fa-user"></i></span>
                                    <!-- select -->
                                    <select name="user" class="form-select selectsearch useraddress"
                                        data-show-subtext="true" data-live-search="true" id="userSelect" required>
                                        <option value="">Select Customer</option>
                                        @forelse($customers as $user)
                                            <option value="{{ $user->id }}" data-userid="{{ $user->id }}">
                                                {{ __($user->name) }} / {{ __($user->phone) }}</option>
                                        @empty
                                            <p>Customer Not Found</p>
                                        @endforelse

                                    </select>
                                    <!-- add user btn -->
                                    <button class="input-group-text rounded-0 bg-navy add_btn"
                                        data-bs-target="#addcustomer_modal" data-bs-toggle="modal">
                                        <i class="fa-solid fa-circle-plus"></i>
                                    </button>
                                    <!-- add user modal -->
                                    <div class="modal fade" id="addcustomer_modal">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!-- modal header -->
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Add a new Customer</h2>
                                                    <button class="btn btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <!-- modal body -->
                                                <form action="{{ route('customer.ajax.store.pos') }}" method="Post"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="addcontact_row">
                                                            <div class="errmsgcontainer"></div>
                                                            <div class="row gy-3">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="" class="form-label"><span
                                                                                class="text-danger">*</span>Name:</label>
                                                                        <!-- Reference no -->
                                                                        <input type="text" name="name"
                                                                            class="form-control rounded-0"
                                                                            placeholder="Input Your Name" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="" class="form-label"><span
                                                                                class="text-danger">*</span>Mobile:</label>
                                                                        <!-- date -->
                                                                        <input type="tel" name="phone"
                                                                            class="form-control rounded-0"
                                                                            placeholder="Mobile Number" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""
                                                                            class="form-label">Email:</label>
                                                                        <!-- date -->
                                                                        <input type="email" name="email"
                                                                            class="form-control rounded-0"
                                                                            placeholder="Input Email">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="division_id" class="form-label"><span
                                                                            class="text-danger">*</span>
                                                                        Division</label>
                                                                    <select class="form-control select-new"
                                                                        aria-label="Default select example"
                                                                        name="division_id" id="division_id" required>
                                                                        <option selected>Select Division</option>
                                                                        @foreach (get_divisions() as $division)
                                                                            <option value="{{ $division->id }}">
                                                                                {{ ucwords($division->division_name_en) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('division_id')
                                                                        <span>{{ $message }}</span>
                                                                    @enderror

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="district_id" class="form-label"><span
                                                                            class="text-danger">*</span>
                                                                            City</label>
                                                                    <select class="form-control select-new"
                                                                        aria-label="Default select example"
                                                                        name="district_id" id="district_id" required>
                                                                        <option selected="" value="">Select
                                                                            city</option>
                                                                    </select>
                                                                    @error('district_id')
                                                                        <span>{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="upazilla_id" class="form-label">
                                                                        Zone</label>
                                                                    <select class="form-control select-new"
                                                                        aria-label="Default select example"
                                                                        name="upazilla_id" id="upazilla_id">
                                                                        <option selected>Select Zone</option>
                                                                    </select>
                                                                    @error('upazilla_id')
                                                                        <span>{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="" class="form-label"><span
                                                                                class="text-danger">*</span>House/Road/Area:</label>
                                                                        <input type="text" name="address"
                                                                            class="form-control rounded-0"
                                                                            placeholder="House/Road/Area" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button"
                                                            class="btn_main footer_innerbtn misty-color usersave">Save</button>
                                                        <button type="button"
                                                            class="btn_main footer_innerbtn bg-navy "
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <!-- select customer -->
                                <div class="input-group">
                                    <select name="staff_id" id="staffid" class="form-select" required>
                                        @if (Auth::guard('admin')->user()->role == '1')
                                            <option value="">-- Select Staff --</option>
                                        @endif
                                        @foreach ($staffs as $staff)
                                            @if (Auth::guard('admin')->user()->role == '5')
                                                @if (Auth::guard('admin')->user()->id == $staff->user_id)
                                                    <option value="{{ $staff->user_id }}" selected>
                                                        {{ $staff->user->name }}</option>
                                                @endif
                                            @elseif(Auth::guard('admin')->user()->role == '1')
                                                <option value="{{ $staff->user_id }}">{{ $staff->user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6 class="address_name"></h6>
                            </div>
                            <div class="col-md-3">
                                <div class="memberbtn">
                                </div>
                            </div>
                            <div class="col-md-3 editbtn">
                            </div>
                            <input type="hidden" name="address" id="" class="address_input">
                            <input type="hidden" name="division_id" id="" class="divition_input">
                            <input type="hidden" name="address" id="" class="district_input">
                            <input type="hidden" name="address" id="" class="upazilla_input">
                        </div>
                    </div>
                    <!-- product table content -->
                    <div class="product__table_content">
                        <!-- product table -->
                        <div class="product__table">
                            <!-- table -->
                            <table class="table table-condensed table-bordered table-responsive">
                                <!-- table header -->
                                <thead>
                                    <tr>
                                        <th class="text-start">Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Subtotal</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <!-- table body -->
                                <tbody class="cartItem__product">

                                </tbody>
                            </table>
                        </div>
                        <!-- table footer -->
                        <div class="product_cart_footer">

                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="d-flex flex-column gap-1 transection__section">
                                        <strong>Transaction Number:</strong>
                                        <input type="text" class="form-control rounded-0"
                                            placeholder="Search Transaction No" id="transectionList" name="search"
                                            autocomplete="off">
                                        <div class="transection_show transection_number_show">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group single__form">
                                        <b>Advanced amount:</b>
                                        <select id="advanced_amount_all" name="transection_amount"
                                            class="form-select">
                                            <option>Select an amount</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4 ">
                                    <div class="form-group single__form">
                                        <div class="d-flex gap-1 flex-column">
                                            <strong>Shipping(+):</strong>
                                            <select name="shipping_charge" id="shipping_charges" class="form-select">
                                                @foreach ($shippings as $shipping)
                                                    <option value="{{ $shipping->shipping_charge }}"
                                                        data-type="{{ $shipping->type }}"
                                                        data-name="{{ $shipping->name }}">
                                                        @if ($shipping->type == 1)
                                                            Inside Dhaka
                                                        @elseif ($shipping->type == 2)
                                                            Outside Dhaka
                                                        @else
                                                            Outside Dhaka City
                                                        @endif
                                                        - {{ $shipping->shipping_charge }} ({{ $shipping->name }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-12 col-lg-6 col-xxl-3">
                                    <div class="form-group single__form">
                                        <b>Items:</b>
                                        <span class="total_quantity" id="total_quantity">0</span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-6 col-xxl-3">
                                    <div class="form-group single__form">
                                        <div class="d-flex flex-column gap-1">
                                            <strong>Discount Type:</strong>
                                            <select id="discount_type" class="form-select">
                                                <option value="1">Flat</option>
                                                <option value="2">Percent</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-6 col-xxl-3">
                                    <div class="form-group single__form">
                                        <div class="d-flex flex-column gap-1">
                                            <strong>Discount(-):</strong>
                                            <input type="text" id="total_discount" value="0"
                                                class="rounded-0 vatInput form-control d-block">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-6 col-xxl-3">
                                    <div class="form-group single__form">
                                        <div class="d-flex flex-column gap-1">
                                            <strong>Others(+):</strong>
                                            <input type="text" id="total_other" value="0"
                                                class="rounded-0 vatInput form-control d-block">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xxl-3">
                                    <div class="form-group single__form">
                                        <b>SubTotal:</b>
                                        <span><span class="totalPrice">0</span>
                                            <span>{{ __($setting->site_currency) }}</span></span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xxl-3">
                                    <div class="form-group single__form">
                                        <b>GrandTotal:</b>
                                        <span><span class="grandTotalPrice" id="grandTotal">0</span>
                                            <span>{{ __($setting->site_currency) }}</span></span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xxl-3">
                                    <div class="form-group single__form">
                                        <b>Total Pay:</b>
                                        <input type="text" id="paidAmount" name="paid_amount"
                                            class="rounded-0 vatInput form-control d-block" placeholder="Paid Amount"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xxl-3">
                                    <div class="form-group single__form">
                                        <b>DueAmount:</b>
                                        <input type="text" id="dueAmount" name="due_amount"
                                            class="rounded-0 vatInput form-control d-block" readonly
                                            placeholder="Due Amount">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- product column -->
            <div class="col-xl-7 col-lg-6">
                <form action="">
                    <div class="row mb-3 gy-2">
                        <div class="col-sm-6 pos_product_search">
                            <input class="form-control " type="text" name="search_product"
                                id="search_product" placeholder="Search by Name" autocomplete="off">
                            <div class="request__product_show">

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="custom_select">
                                <select name="category_id" id="category_add"
                                    class="form-control select-active w-100 form-select select-nice">
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- pos product -->
                <div class="pos__productcontent">
                    <!-- pos product -->
                    <div class="pos__product_wrapper product-load">
                        <div class="row g-2 product-list">
                           @include('backend.pos.product')
                        </div>
                    </div>
                    {{-- <button class="btn btn-xs d-flex mx-auto my-4" id="seemore">Load More <i
                            class="fi-rs-arrow-small-right"></i></button> --}}

                </div>
            </div>
        </div>
    </div>
    <!-- content main end-->


    <!-- pos footer -->
    <footer class="pos_footer">
        <!-- container -->
        <div class="container-fluid">
            <!-- wrapper -->
            <div class="pos_footer_wrapper">
                <!-- pos footer button -->
                <div class="pos_footer_btn d-flex align-items-center gap-2">
                    <!-- draft btn -->
                    <form action="{{ route('pos.order.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="sub_total" class="sub_total">
                        <input type="hidden" name="grand_total" class="grandPrice" required>
                        <input type="hidden" name="totalbuyingPrice" class="totalbuyingPrice">
                        <input type="hidden" name="discount" class="discount_price">
                        <input type="hidden" name="shipping_charge" class="shipping_charge">
                        <input type="hidden" name="shipping_type" class="shipping_type" value="0">
                        <input type="hidden" name="shipping_name" class="shipping_name" value="">
                        <input type="hidden" name="paid_amount" class="paid_amount" required>
                        <input type="hidden" name="due_amount" class="due_amount" value="0">
                        <input type="hidden" name="staff_id" class="staffid">
                        <input type="hidden" name="user_id" class="userId" value="">
                        <input type="hidden" name="advanced_amount" class="advanced_amount">
                        <input type="hidden" name="advanced_id" class="advanced_id">
                        <input type="hidden" name="transaction_no" class="transaction_no">
                        <input type="hidden" name="others" class="othersAdd">
                        <!-- cash payment -->
                        <button type="submit" class="btn_main bg-success footer_innerbtn" style="color:white">
                            <span><i class="fas fa-money-check-alt"></i></span> Confirm </button>
                    </form>
                    <form action="{{ route('pos.order.store.withPrint') }}" method="post" target="_blank">
                        @csrf
                        <input type="hidden" name="sub_total" class="sub_total">
                        <input type="hidden" name="grand_total" class="grandPrice" required>
                        <input type="hidden" name="totalbuyingPrice" class="totalbuyingPrice">
                        <input type="hidden" name="discount" class="discount_price">
                        <input type="hidden" name="shipping_charge" class="shipping_charge">
                        <input type="hidden" name="shipping_type" class="shipping_type" value="0">
                        <input type="hidden" name="shipping_name" class="shipping_name" value="">
                        <input type="hidden" name="paid_amount" class="paid_amount" required>
                        <input type="hidden" name="due_amount" class="due_amount" value="0">
                        <input type="hidden" name="staff_id" class="staffid">
                        <input type="hidden" name="user_id" class="userId" value="">
                        <input type="hidden" name="advanced_amount" class="advanced_amount">
                        <input type="hidden" name="advanced_id" class="advanced_id">
                        <input type="hidden" name="transaction_no" class="transaction_no">
                        <input type="hidden" name="others" class="othersAdd">
                        <!-- cash payment -->
                        <button type="submit" class="btn_main bg-primary footer_innerbtn" id="reloadButton">
                            <span><i class="fas fa-money-check-alt"></i></span> Confirm & Print </button>
                    </form>
                    <!-- total payable -->
                    <div class="total_payable">
                        <span>Total Payable:</span>
                        <strong class="grandTotalPayable"></strong> <span>{{ __($setting->site_currency) }}</span>
                    </div>
                    <form action="{{ route('pos.order.cancle') }}" method="post">
                        @csrf
                        <!-- cencel -->
                        <button type="submit" class="btn_main misty-color footer_innerbtn">
                            <span></span> Reset </button>
                    </form>
                </div>
            </div>
        </div>
    </footer>
    <!-- pos footer end -->

@endsection

@push('js')
    <script>
        (function($) {
            "use strict"
            $(document).ready(function() {

                var selectedValue = $('select[name="staff_id"]').val();
                $('.staffid').val(selectedValue);
                $('select[name="staff_id"]').change(function() {
                    var selectedValue = $(this).val();
                    $('.staffid').val(selectedValue);
                });

                $('select[name="user"]').change(function() {
                    var selectedValue = $(this).val();
                    $('.userId').val(selectedValue);
                });
            });

            // card
            $(document).on('change', '.paymentMethod', function() {
                // Your existing code for handling payment method change here
                const cardPaymentRow = $(this).closest('.addRow').find(".cardpayment_row");
                const chequePaymentRow = $(this).closest('.addRow').find(".cheque_payment_row");
                const bankPaymentRow = $(this).closest('.addRow').find(".bank_payment_row");
                const customerPaymentRow = $(this).closest('.addRow').find(".cutomer_payment_row");

                const selectedValue = $(this).val();
                cardPaymentRow.toggleClass("show", selectedValue === "Card");
                chequePaymentRow.toggleClass("show", selectedValue === "bKash");
                bankPaymentRow.toggleClass("show", selectedValue === "nagad");
                customerPaymentRow.toggleClass("show", selectedValue === "rocket");
            });


        })(jQuery);
    </script>
    <script>
        function updateClock() {
            var clockElement = document.getElementById('clock');
            var dhakaTime = new Date();
            dhakaTime.setHours(dhakaTime.getUTCHours() + 6);

            var hours = dhakaTime.getHours();
            var minutes = dhakaTime.getMinutes();
            var seconds = dhakaTime.getSeconds();

            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;

            var timeString = hours.toString().padStart(2, '0') + ':' +
                minutes.toString().padStart(2, '0') + ':' +
                seconds.toString().padStart(2, '0') + ' ' + ampm;

            clockElement.innerText = timeString;
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>


    <script>
        document.getElementById("reloadButton").addEventListener("click", function() {
            // document.getElementById("myForm").submit();
            setTimeout(function() {
                location.reload();
            }, 60);
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>
    <script>
        $(function() {
            $(".selectsearch").select2();
        });
    </script>

    <script>
       $(document).ready(function() {
            updateShippingDetails();
            $('#shipping_charges').change(function() {
                updateShippingDetails();
            });
        });
        function updateShippingDetails() {
            var selectedOption = $('#shipping_charges').find(':selected');
            var shippingType = selectedOption.data('type');
            var shippingName = selectedOption.data('name');
            $('.shipping_type').val(shippingType);
            $('.shipping_name').val(shippingName);
        }
    </script>

    {{-- for number show in input field --}}
    <script type="text/javascript">
        function isNumberKey(txt, evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode == 46) {
                //Check if the text already contains the . character
                if (txt.value.indexOf('.') === -1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (charCode > 31 &&
                    (charCode < 48 || charCode > 57))
                    return false;
            }
            return true;
        }
    </script>
    <script>
        $(document).on("keyup", "#transectionList", function() {
            var text = $(this).val();
            if (text.length > 0) {
                $.ajax({
                    data: {
                        search: text
                    },
                    url: "{{ route('advancedPayment.searchtransection') }}",
                    method: 'get',
                    beforSend: function(request) {
                        return request.setReuestHeader('X-CSRF-Token', (
                            "meta[name='csrf-token']"))
                    },
                    success: function(result) {

                        if (result) {
                            $(".transection_show").html(result);
                        } else {
                            $('.transection_show').html("No results found.");
                        }
                    }
                }); // end ajax
            } // end if
            if (text.length < 1) {
                $(".transection_show").html("");
                $('select[name="transection_amount"]').empty();
                $('.advanced_id').val('');
                var grandPrice = $('.grandPrice').val();
                $('#paidAmount, .paid_amount').val('');
                 $('.grandTotalPayable').text('');
                $('.due_amount').val('');
                $('.transaction_no').val('');
                $('input[name="due_amount"]').val(grandPrice);
            }

        });

        $(document).on('click', '.transection', function() {
            var transaction_no = $(this).data('transaction_no');
            $('.transaction_no').val(transaction_no);
            $("#transectionList").val(transaction_no);
            $("#transectionall").hide();
            var url = '{{ route('transection.amount.ajax', '') }}' + '/' + transaction_no;
            if (transaction_no) {
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="transection_amount"]').empty();
                        // Add total_amount as the default selected value
                        var totalAmountValue = parseFloat(data.total_amount.advance_amount);
                        //var grand = parseFloat($('.grandPrice').val());
                        var grand = parseFloat($('input[name="grand_total"]').val());
                        payable = Math.min(totalAmountValue, grand);
                        $('#paidAmount ,.paid_amount').val(payable);
                        $('.grandTotalPayable').text(grand);
                        if (grand > payable) {
                            var due = grand - payable;
                            $('input[name="due_amount"]').val(due);
                        } else {
                            $('input[name="due_amount"]').val(0);
                        }
                        $('select[name="transection_amount"]').append(
                            '<option value="" selected>Total Amount: ' + totalAmountValue +
                            '</option>'
                        );

                        if (data.amounts && data.amounts.length > 0) {
                            $.each(data.amounts, function(key, value) {
                                var formattedDate = new Date(value.created_at)
                                    .toLocaleDateString('en-US', {
                                        day: '2-digit',
                                        month: '2-digit',
                                        year: 'numeric'
                                    }).split('/').join('.');
                                $('select[name="transection_amount"]').append(
                                    '<option value="' + value.id + '">' + 'Amount: ' + value
                                    .advance_amount + ' ,  (date:' + formattedDate +
                                    ')</option>'
                                );
                            });
                        } else {
                            // Handle case when no amounts are returned
                            $('select[name="transection_amount"]').append(
                                '<option value="">No amounts available</option>');
                        }
                    },
                    error: function() {
                        // Handle error case
                        alert('Error occurred while fetching data.');
                    }
                });
            } else {
                alert('Invalid transaction number');
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#userSelect').on('change', function() {
                var selectedValue = $(this).val();
                $('.userId').val(selectedValue);
                var data = {
                    user_id: selectedValue,
                }
                /* $.ajax({
                     url: "{{ route('get.advanced.amount') }}",
                     method: "post",
                     data: data,
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                     success: function(response) {
                         console.log(response)
                         if (response.success) {
                             $('#advancedamount').text(response.advanced_amount);
                             $('input[name="advanced_amount"]').val(response.advanced_amount);
                             $('input[name="transaction_no"]').val(response.transection_Num);
                         } else {
                             systemAlert('danger', response);
                         }
                     }
                 });*/

            });
            $('#staffid').on('change', function() {
                var selectedValue = $(this).val();
                $('.staffid').val(selectedValue);
            });
        });
    </script>


    <!--  Division To District Show Ajax -->
    <script type="text/javascript">
          $(document).on('change', "#division_id", function() {
            var division_id = $(this).val();
            if (division_id) {
                $.ajax({
                    url: "{{ url('/division-district/ajax') }}/" + division_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="district_id"]').html(
                            '<option value="" selected="" disabled="">Select City</option>'
                        );
                        $.each(data, function(key, value) {
                            $('select[name="district_id"]').append(
                                '<option value="' + value.id + '">' +
                                capitalizeFirstLetter(value.district_name_en) +
                                '</option>');
                        });
                        $('select[name="upazilla_id"]').html(
                            '<option value="" selected="" disabled="">Select Zone</option>'
                        );
                    },
                });
            } else {
                alert('danger');
            }
        });
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Address Realtionship Division/District/Upazilla Show Data Ajax //
        $(document).on('change', "#address_id", function() {
            var address_id = $(this).val();
            $('.selected_address').removeClass('d-none');
            if (address_id) {
                $.ajax({
                    url: "{{ url('/address/ajax') }}/" + address_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#dynamic_division').text(capitalizeFirstLetter(data
                            .division_name_en));
                        $('#dynamic_division_input').val(data.division_id);
                        $("#dynamic_district").text(capitalizeFirstLetter(data
                            .district_name_en));
                        $('#dynamic_district_input').val(data.district_id);
                        $("#dynamic_upazilla").text(capitalizeFirstLetter(data
                            .upazilla_name_en));
                        $('#dynamic_upazilla_input').val(data.upazilla_id);
                        $("#dynamic_address").text(data.address);
                        $('#dynamic_address_input').val(data.address);
                    },
                });
            } else {
                alert('danger');
            }
        });
    </script>

    <!--  District To Upazilla Show Ajax -->

<script type="text/javascript">
    $(document).on('change', "#district_id", function() {
            var district_id = $(this).val();
            if (district_id) {
                $.ajax({
                    url: "{{ url('/district-upazilla/ajax') }}/" + district_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="upazilla_id"]').html(
                            '<option value="" selected="" disabled="">Select Zone</option>'
                        );
                        $.each(data, function(key, value) {
                            $('select[name="upazilla_id"]').append(
                                '<option value="' + value.id + '">' + value
                                .name_en + '</option>');
                        });
                    },
                });
            } else {
                alert('danger');
            }
        });
</script>


    {{-- filter category --}}
    <script type="text/javascript">
        // $(document).on('change', "#category_add", function() {
        //     var category_id = $(this).val();
        //     var url = 'pos/product/filter/'+ category_id;
        //     $.ajax({
        //         url: url,
        //         type: "GET",
        //         success: function(response) {
        //             $('.product-list').empty();
        //             var products = JSON.parse(response);
        //             products.forEach(function(product) {
        //             var productHTML = '';
        //             if (product.is_varient == 1  ) {
        //                 console.log("Variant Product Stocks:", product.stocks);
        //                 product.stocks.forEach(stock => {
        //                 productHTML += '<div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-3 col-xxl-2 product-thumb product_row_loader addToCartBtn" data-id="' + stock.id + '" data-product_id="' + product.id + '">';
        //                 productHTML += '<div class="card single__product__najmul" style="cursor: pointer">';
        //                 productHTML += '<div class="card-body product__body">';
        //                 productHTML += '<p>Stock Name: ' + stock.name + '</p>';
        //                 productHTML += '</div></div></div>';
        //             });
        //         }
        //             else {
        //                 console.log("Non-Variant Product or No Stocks:", product);
        //                 productHTML += '<div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-3 col-xxl-2 product-thumb product_row_loader addToCartBtn" data-product_id="' + product.id + '">';
        //                 productHTML += '<div class="card single__product__najmul">';
        //                 productHTML += '<div class="card-body product__body">';
        //                 // Customize your HTML structure for non-variant products as needed
        //                 productHTML += '<h6 class="product__name">' + product.name_en + '</h6>';
        //                 // Add more HTML components based on your requirements
        //                 productHTML += '</div></div></div>';
        //             }
        //             $('.product-list').append(productHTML);
        //             //console.log(productHTML)
        //         });
        //         },
        //     });
        // });
    </script>
    {{-- filter category --}}
@endpush