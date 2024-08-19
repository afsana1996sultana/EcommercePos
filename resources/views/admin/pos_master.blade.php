<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $setting->site_name }} @yield('title')</title>
     @php
            $logo = get_setting('site_favicon');
        @endphp
        @if($logo != null)
            <link rel="shortcut icon" type="image/x-icon" href="{{ asset(get_setting('site_favicon')->value ?? 'null') }}" />
        @else
            <link rel="shortcut icon" type="image/x-icon" href="{{ asset('upload/no_image.jpg') }}" alt="{{ env('APP_NAME') }}" />
        @endif
    <!-- bootstarp link -->
    <link rel="stylesheet" href="{{ asset('backend/pos/css/bootstrap.min.css') }}">
    <!-- font awesome -->
    <link rel="stylesheet" href="{{ asset('backend/pos/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/pos/css/fontawesome.min.css') }}">
    <!-- css file link -->
    <link href="{{ asset('backend/pos/css/toastr.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('backend/pos/css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/pos/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/pos/css/responsive.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

</head>

<body class="main_body">

    @yield('content')

    <!-- jquery link -->
    <script src="{{ asset('backend/pos/js/jquery.min.js') }}"></script>
    <!-- bootstrap js -->
    <script src="{{ asset('backend/pos/js/bootstrap.bundle.min.js') }}"></script>
    <!-- main js -->
    <script src="{{ asset('backend/pos/js/toastr.min.js') }}"></script>
    <script src="{{ asset('backend/pos/js/calculator.js') }}"></script>
    <script src="{{ asset('backend/pos/js/app.js') }}"></script>
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('js')

    <script>
        'use strict';
        (function($) {

            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }


            @if ($errors->any())
                @foreach ($errors->all() as $emsg)
                    toastr.error('{{ $emsg }}');
                @endforeach
            @endif
            @if (session()->has('alert'))
                @if (session('alert')[0] == 'danger')
                    toastr.error('{{ session('alert')[1] }}');
                @elseif (session('alert')[0] == 'success')
                    toastr.success('{{ session('alert')[1] }}');
                @else
                    toastr.error('{{ session('alert')[1] }}');
                @endif
            @endif
            function systemAlert(type, message) {
                if (type == 'danger') {
                    toastr.error(message);
                } else if (type == 'success') {
                    toastr.success(message);
                } else {
                    toastr.error(message);
                }
            }
            $(document).on('click', '.usersave ', function() {
                //alert('hi');
                var nameadd = $('input[name="name"]').val();
                var emailadd = $('input[name="email"]').val();
                var phoneadd = $('input[name="phone"]').val();
                var divitionadd = $('select[name="division_id"]').val();
                var districtadd = $('select[name="district_id"]').val();
                var upazillaadd = $('select[name="upazilla_id"]').val();
                var addressadd = $('input[name="address"]').val();
                var data = {
                    name: nameadd,
                    email: emailadd,
                    phone: phoneadd,
                    division_id: divitionadd,
                    district_id: districtadd,
                    upazilla_id: upazillaadd,
                    address: addressadd,
                }
                $.ajax({
                    url: "{{ route('customer.ajax.store.pos') }}",
                    method: "post",
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#addcustomer_modal').modal('hide');
                            $('input[name="name"]').val('');
                            $('input[name="email"]').val('');
                            $('input[name="phone"]').val('');
                            $('select[name="division_id"]').val('');
                            $('select[name="district_id"]').val('');
                            $('select[name="upazilla_id"]').val('');
                            $('input[name="address"]').val('');
                            $.ajax({
                                url: '{{ route('get.customer.data.pos') }}',
                                type: "GET",
                                dataType: "json",
                                success: function(data) {
                                    $('#userSelect').empty();
                                    $.each(data, function(key, value) {
                                        var optionText = capitalizeFirstLetter(value.name) + ' / ' + value.phone;

                                        if (key === data.length - 1) {
                                            $('#userSelect').append(
                                                $('<option>', {
                                                    value: value.id,
                                                    text: optionText,
                                                    selected: (response?.data?.id == value.id)
                                                })
                                            );
                                            $('#userSelect').trigger('change');
                                            $('#userSelect').select2('destroy').select2();
                                        } else {
                                            $('#userSelect').append(
                                                $('<option>', {
                                                    value: value.id,
                                                    text: optionText
                                                })
                                            );
                                        }
                                    });
                                    $('#userSelect').append(
                                        '<option value="0">Walk-In Customer</option>'
                                    );
                                }
                            });
                            systemAlert('success', response.success);
                        } else if (response.error) {
                            systemAlert('danger', response.error.join(', '));
                        } else {
                            systemAlert('danger', response);
                        }
                    }
                });
            });
            $(document).on('click', '.customerupdate ', function() {
                var userid = $('input[name="userid"]').val();
                var nameadd = $('#edit_name').val();
                var emailadd = $('#edit_email').val();
                var phoneadd = $('#edit_phone').val();
                var divitionadd = $('.edit_divition').val();
                var districtadd = $('.edit_district').val();
                var upazillaadd = $('.edit_upazilla').val();
                var addressadd = $('#edit_address').val();
                var data = {
                    userid: userid,
                    name: nameadd,
                    email: emailadd,
                    phone: phoneadd,
                    division_id: divitionadd,
                    district_id: districtadd,
                    upazilla_id: upazillaadd,
                    address: addressadd,
                }
                $.ajax({
                    url: "{{ route('customer.ajax.update.pos') }}",
                    method: "post",
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#updatecustomer_modal').modal('hide');
                            $('.address_name').text('Address: ' + response.data.address);
                            $.ajax({
                                url: '{{ route('get.update.customer.data.pos') }}',
                                type: "GET",
                                dataType: "json",
                                success: function(data) {
                                    $('#userSelect').empty();
                                    $.each(data, function(key, value) {
                                        var optionText = capitalizeFirstLetter(value.name) + ' / ' + value.phone;

                                        if (key === data.length - 1) {
                                            $('#userSelect').append(
                                                $('<option>', {
                                                    value: value.id,
                                                    text: optionText,
                                                    selected: (response?.data?.id == value.id)
                                                })
                                            );
                                            $('#userSelect').trigger('change');
                                            $('#userSelect').select2('destroy').select2();
                                        } else {
                                            $('#userSelect').append(
                                                $('<option>', {
                                                    value: value.id,
                                                    text: optionText
                                                })
                                            );
                                        }
                                    });

                                    $('#userSelect').append(
                                        '<option value="0">Walk-In Customer</option>'
                                    );
                                }
                            });
                            systemAlert('success', response.success);
                        } else if (response.error) {
                            systemAlert('danger', response.error);
                        } else {
                            systemAlert('danger', response);
                        }
                    }
                });
            });

            function getCartData() {
                $.ajax({
                    method: "get",
                    url: '{{ route('get.pos.CartData') }}',
                    success: function(response) {
                        $('.cartItem__product').html(response.cart_data);
                        $('.totalPrice').text(response?.totalPrice);
                        var subtotal = parseFloat($('.totalPrice').text());
                        $('.grandTotalPrice').text(response.totalPrice);
                        $('.sub_total').val(response.totalPrice);
                        $('.totalbuyingPrice').val(response.totalbuyingPrice);
                        $('.taxAmount').val(response.taxAmount);
                        $('.shipping_charge').val(response.shipping_charge);
                        $('.discount_price').val(response.discount_price);
                        $('.total_quantity').text(response.count);
                        $('input[name="grand_total"]').val(response.totalPrice);
                        var shipping_amount = parseFloat($('#shipping_charges').val()) || 0;
                        $('input[name="shipping_charge"]').val(shipping_amount);
                        var discount = parseFloat($('#total_discount').val()) ||0;
                        var discount_type = parseFloat($('#discount_type').val());
                        var total_other = parseFloat($('#total_other').val());
                        var grandTotalInput = parseFloat($('input[name="grand_total"]').val());
                        var transectionNum = $('.transaction_no').val();
                       if (discount_type == 1) {
                          var amountOfDiscount = discount;
                           $('input[name="discount"]').val(discount);
                        } else {
                            var percentageDiscount = subtotal * discount / 100;
                            amountOfDiscount = percentageDiscount;
                             $('input[name="discount"]').val(percentageDiscount);
                        }
                        if(transectionNum){
                            var selectedValue = $('select[name="transection_amount"]').find('option:selected');
                            var advanceAmountMatch = selectedValue.text().match(/Amount: (\d+(\.\d+)?)/);
                            var advanceAmountget = advanceAmountMatch ? parseFloat(advanceAmountMatch[1]) : 0;
                           // var grandTotal =  parseFloat($('.grandPrice]').val()) || 0;
                           // var grandTotal = parseFloat($('#grandTotal').text());
                          var grandTotal=((grandTotalInput+total_other+shipping_amount)-amountOfDiscount);
                            var advanceAmount = Math.min(advanceAmountget, grandTotal);
                            var dueshow=grandTotal - advanceAmount;
                           // var latestamount=((advanceAmount+total_other)-amountOfDiscount);
                           $('.grandTotalPayable').text(grandTotal);
                            $('#paidAmount').val(advanceAmount);
                            $('.paid_amount').val(advanceAmount);
                            $('#grandTotal').text(grandTotal);
                            $('input[name="grand_total"]').val(grandTotal);
                            if (advanceAmountget > grandTotal) {
                                $('input[name="due_amount"]').val('');
                            } else {
                                $('input[name="due_amount"]').val(grandTotal - advanceAmount);
                            }
                        }
                        else{
                            var totaladd = ((grandTotalInput + total_other+shipping_amount)-amountOfDiscount);
                            $('#paidAmount').val('');
                            $('.paid_amount').val('');
                            $('input[name="due_amount"]').val(totaladd);
                            $('.grandTotalPrice').text(totaladd);
                            $('input[name="grand_total"]').val(totaladd);
                            $('.grandTotalPayable').text(totaladd);
                        }
                    }
                });
            }
            getCartData();


            /* add to cart */
            $(document).on('click', '.addToCartBtn', function() {
                var product_id = $(this).attr('data-product_id');
                var dataid = $(this).attr('data-id');
                var data = {
                    product_id: product_id,
                    stock_id: dataid
                }
                if (dataid !== undefined && dataid !== null) {
                    data.stock_id = dataid;
                }
                $.ajax({
                    url: "{{ route('add.pos.product') }}",
                    method: "get",
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            getCartData();
                            systemAlert('success', response.success);
                        } else if (response.error) {
                            systemAlert('danger', response.error);
                        } else {
                            systemAlert('danger', response);
                        }
                    }
                });
            });

            $(document).on("keyup", ".productSearchShow", function() {
                var text = $(this).val();
                if (text.length > 0) {
                    $.ajax({
                        data: {
                            searchshow: text
                        },
                        url: "{{ route('product.search.show') }}",
                        method: 'get',
                        beforSend: function(request) {
                            return request.setReuestHeader('X-CSRF-Token', (
                                "meta[name='csrf-token']"))

                        },
                        success: function(result) {
                            if (result) {
                                $(".request__product_show").html(result);
                            } else {
                                $('.request__product_show').html("No results found.");
                            }
                        }

                    });
                }
                if (text.length < 1) $(".request__product_show").html("");
            });

            //remove
            $(document).on('click', '.remove-posCart', function(e) {
                var btn = $(this);
                var id = btn.data('id');
                var url = '{{ route('pos.delete.item', '') }}' + '/' + id;
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: url,
                    method: "GET",
                    success: function(response) {
                        if (response.success) {
                            //location.reload();
                            systemAlert('success', response.success);
                            getCartData();

                        } else {
                            systemAlert('danger', response.error);
                        }
                    }
                });
            });
            
             //gift add
                $(document).on('click','.gift_status',function(){
                   // console.log('liza')
                    var product_id = $(this).closest('.cart__product').find('.product_id').val();
                    // console.log(product_id);
                    $.ajax({
                        method:"get",
                        url:'{{ route('pos.gift_status') }}',
                        data: {
                            product_id:product_id
                        },
                        success:function(response){
                            if (response.success) {
                                getCartData();
                                Updatediscount();
                                systemAlert('success', response.success);
                            } else {
                                systemAlert('danger', response.error);
                            }
                        }
                    })
                })

            //Update Cart
            $(document).on('click', '.changeQuantity', function() {
                var product_id = $(this).closest('.cart__product').find('.product_id').val();
                var stock_id = $(this).closest('.cart__product').find('.stock_id').val();
                var type = $(this).data('type');
                var data = {
                    'product_id': product_id,
                    'stock_id': stock_id,
                    'type': type,
                }
                if (stock_id !== undefined && stock_id !== null) {
                    data.stock_id = stock_id;
                }
                $.ajax({
                    method: "get",
                    url: '{{ route('pos.cart.update') }}',
                    data: data,
                    success: function(response) {
                        systemAlert(response.status, response.message);
                        if (response.status == 'success') {
                            var currentPrice = parseFloat($('#totalPrice').text());
                            if (response.type == '+') {
                                currentPrice += parseFloat(response.price);
                            } else {
                                currentPrice -= parseFloat(response.price);
                            }
                            $('.totalPrice').text(currentPrice);
                            getCartData();
                        }
                    }
                });
            });

           $(document).ready(function() {
                $('.useraddress').on('change', function() {
                    var selectedValue = $(this).val();
                    var data = {
                        user_id: selectedValue,
                    }
                    $.ajax({
                        url: "{{ route('get.user.address.pos') }}",
                        method: "get",
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            //console.log(response)
                            if (response.success) {
                                $('.address_name').empty();
                                $('.address_input').val('');
                                $('.editbtn').empty();
                                $('.memberbtn').empty();
                                //$('#shipping_charges').empty();
                                var getmember = response.member;
                                var divisionadd = '{{ get_divisions() }}';
                                var decodedDivision = divisionadd.replace(/&quot;/g, '"');
                                var divisionArray = JSON.parse(decodedDivision);
                                var user = response?.useradd;
                                var addressadd = response?.address ?? 0;
                                var divition = addressadd.division_id;
                                let options = '';
                                divisionArray.forEach(element => {
                                    options +=
                                        `<option value="${element?.id}" ${element?.id == divition ? 'selected':''}>${element.division_name_en}</option>`;
                                });
                                var divitionId = addressadd.division_id;
                                var districtsget = response?.division;
                                var districtsData = districtsget;
                                let districtOptions = '';
                                var districtId = addressadd.district_id ?? 0;
                                districtsData.forEach(element => {
                                    districtOptions +=
                                        `<option value="${element.id}" ${element?.id == districtId ? 'selected':''}>${element.district_name_en}</option>`;
                                });

                                var Upazillasget = response?.district;
                                var upazillasData = Upazillasget;
                                let UpazillaOptions = '';
                                var upazillaId = addressadd.upazilla_id ?? 0;
                                upazillasData.forEach(element => {
                                    UpazillaOptions +=
                                        `<option value="${element.id}" ${element?.id == upazillaId ? 'selected':''}>${element.name_en}</option>`;
                                });
                                if (user)
                                    $('.address_name').text('Address: ' + response.address.address);
                                $('.address_input').val(response.address.address);
                                $('.memberbtn').append(`
                                    <button type="button" class="btn btn-primary btn-sm">${getmember}</button>
                                `);
                                $('.editbtn').append(`
                                <button class="input-group-text rounded-0 bg-navy add_btn"
                                        data-bs-target="#updatecustomer_modal" data-bs-toggle="modal">
                                        Edit
                                    </button>
                                <div class="modal fade" id="updatecustomer_modal">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!-- modal header -->
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Update User</h2>
                                                    <button class="btn btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <!-- modal body -->
                                                <form action="" method="Post"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <input type="hidden" name="userid"
                                                                            class="form-control rounded-0"
                                                                            placeholder="" value=" ${user.id}" required>
                                                        <div class="addcontact_row">
                                                            <div class="row gy-3">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""
                                                                            class="form-label"><span class="text-danger">*</span>Name:</label>
                                                                        <!-- Reference no -->
                                                                        <input type="text" name="name"
                                                                            class="form-control rounded-0"
                                                                            placeholder="" value=" ${user.name}" required id="edit_name">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""
                                                                            class="form-label"><span class="text-danger">*</span>Mobile:</label>
                                                                        <!-- date -->
                                                                        <input type="tel" name="phone"
                                                                            class="form-control rounded-0"
                                                                            placeholder="Mobile Number" value="${user.phone}" required id="edit_phone" >
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""
                                                                            class="form-label">Email:</label>
                                                                        <!-- date -->
                                                                        <input type="email" name="email"
                                                                            class="form-control rounded-0"
                                                                            placeholder="Input Email" value="${user.email !== null ? user.email : ''}" id="edit_email">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="division_id" class="form-label"><span class="text-danger">*</span>Division</label>
                                                                    <select class="form-control select-new edit_divition" aria-label="Default select example" name="division_id" id="division_id" required >
                                                                        ${options}
                                                                        </select>
                                                                    @error('division_id')
                                                                    <span>{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="district_id" class="form-label"><span class="text-danger">*</span> City</label>
                                                                    <select class="form-control select-new edit_district" aria-label="Default select example" name="district_id" id="district_id" required>
                                                                        ${districtOptions}
                                                                    </select>
                                                                    @error('district_id')
                                                                    <span>{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="upazilla_id" class="form-label"><span class="text-danger">*</span> Zone</label>
                                                                    <select class="form-control select-new edit_upazilla" aria-label="Default select example" name="upazilla_id" id="upazilla_id" required>
                                                                        ${UpazillaOptions}
                                                                    </select>
                                                                    @error('upazilla_id')
                                                                    <span>{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for=""
                                                                            class="form-label"><span class="text-danger">*</span>House/Road/Area:</label>
                                                                        <input type="text" name="address"
                                                                            class="form-control rounded-0"
                                                                            placeholder="House/Road/Area" value="${user.address}" required id="edit_address">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button"
                                                            class="btn_main footer_innerbtn misty-color customerupdate">Update</button>
                                                        <button type="button"
                                                            class="btn_main footer_innerbtn bg-navy"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                `)

                            } else {
                                systemAlert('danger', response);
                            }
                        }
                    });
                });
            });


           function Updatediscount() {
                var subtotal = parseFloat($('.totalPrice').text()) || 0;
                var shipping_amount = parseFloat($('#shipping_charges').val()) || 0;
                var discount_type = parseFloat($('#discount_type').val()) || 0;
                var discount_amount = parseFloat($('#total_discount').val()) || 0;
                var total_other = parseFloat($('#total_other').val()) || 0;
                var paidAmount = parseFloat($('#paidAmount').val()) || 0;
                var transectionAmount = $('.transaction_no').val();
                var amountOfDiscount = 0;
                if (discount_type == 1) {
                    discount_amount = Math.min(discount_amount, subtotal);
                    amountOfDiscount = discount_amount;
                    $('#total_discount').val(discount_amount);
                } else {
                    discount_amount = Math.min(discount_amount, 100);
                    var percentageDiscount = subtotal * discount_amount / 100;
                    amountOfDiscount = percentageDiscount;
                    console.log(amountOfDiscount);
                    $('#total_discount').val(discount_amount);
                }
                //var discount_of = (discount_type == 1) ? discount_amount : (subtotal * discount_amount) / 100;
                var after_discount = ((subtotal - amountOfDiscount) + shipping_amount +total_other).toFixed(2);
                var dueAmount = (after_discount - paidAmount).toFixed(2);
                 $('.grandTotalPayable').text(after_discount);
                if(transectionAmount){
                    var selectedValue = $('select[name="transection_amount"]').find('option:selected');
                    var advanceAmountMatch = selectedValue.text().match(/Amount: (\d+(\.\d+)?)/);
                    var advanceAmount = advanceAmountMatch ? parseFloat(advanceAmountMatch[1]) : 0;
                    paidAmount = Math.min(advanceAmount, after_discount);
                     dueAmount = (after_discount - paidAmount).toFixed(2);
                    $('.grandTotalPrice').text(after_discount);
                    //$('.grandTotalPayable').text(after_discount);
                    $('input[name="sub_total"]').val(subtotal);
                    $('input[name="grand_total"]').val(after_discount || 0);
                    $('input[name="grandTotalPrice"]').val(after_discount);
                    $('input[name="discount"]').val(amountOfDiscount);
                    $('input[name="shipping_charge"]').val(shipping_amount);
                   // $('input[name="paid_amount"]').val(paidAmount);
                   $('input[name="others"]').val(total_other);
                    $('#paidAmount').val(paidAmount);
                    $('.paid_amount').val(paidAmount);
                    var grandTotal = $('input[name="grand_total"]').val();
                    if (grandTotal > paidAmount) {
                        //var dueAmount = grandTotal - advanceAmount;
                        $('input[name="due_amount"]').val(dueAmount);
                    }else{
                        $('input[name="due_amount"]').val(0);
                    }
                   // $('input[name="due_amount"]').val(dueAmount);
                }
                if(!transectionAmount){
                    paidAmount = Math.min(paidAmount, after_discount);
                    paidAmount = after_discount;
                    $('input[name="paid_amount"]').val('');
                    $('input[name="due_amount"]').val(paidAmount);
                    $('.grandTotalPrice').text(after_discount);
                   // $('.grandTotalPayable').text(after_discount);
                    $('input[name="sub_total"]').val(subtotal);
                    $('input[name="grand_total"]').val(after_discount || 0);
                    $('input[name="grandTotalPrice"]').val(after_discount);
                    $('input[name="discount"]').val(amountOfDiscount);
                    $('input[name="shipping_charge"]').val(shipping_amount);
                    $('input[name="others"]').val(total_other);
                }
            }
            Updatediscount();
            // Event listeners
            $('#discount_type').on("change", Updatediscount);
            $('#total_discount').on("keyup", Updatediscount);
            $('#total_other').on("keyup", Updatediscount);
            $('#shipping_charges').on("change", Updatediscount);
            $('#paidAmount').on("keyup",function(){
                var value = $(this).val();
                var transectionAmount = $('.transaction_no').val();
                if(transectionAmount){
                    var selectedValue = $('select[name="transection_amount"]').find('option:selected');
                    var advanceAmountMatch = selectedValue.text().match(/Amount: (\d+(\.\d+)?)/);
                    var advanceAmount = advanceAmountMatch ? parseFloat(advanceAmountMatch[1]) : 0;
                    value = advanceAmount;
                }
                $('.paid_amount').val(value);
               // $('.grandTotalPayable').text(value);
                var grandTotal =  parseFloat($('input[name="grand_total"]').val()) || 0;
                value = Math.min(value, grandTotal);
                $(this).val(value)
                var afterPaid = grandTotal - value;
                $('input[name="due_amount"]').val(afterPaid);
                $('input[name="paid_amount"]').val(value);
            })
            $('select[name="transection_amount"]').on('change', function() {
                var selectedValue = $(this).find('option:selected');
                var advanceAmountMatch = selectedValue.text().match(/Amount: (\d+(\.\d+)?)/);
                var advanceAmount = advanceAmountMatch ? parseFloat(advanceAmountMatch[1]) : 0;
                $('.advanced_id').val(selectedValue.val());
                var grandTotal = $('input[name="grand_total"]').val();
                if (advanceAmount > grandTotal) {
                    $('#paidAmount').val(grandTotal);
                   // $('.grandTotalPayable').text(grandTotal);
                    $('.paid_amount').val(grandTotal);
                    $('input[name="due_amount"]').val(0);
                } else {
                    $('#paidAmount').val(advanceAmount);
                   // $('.grandTotalPayable').text(advanceAmount);
                    $('.paid_amount').val(advanceAmount);
                    var dueAmount = grandTotal - advanceAmount;
                    $('input[name="due_amount"]').val(dueAmount);
                }
            });
        })(jQuery)
    </script>
    <script>
        $('.select-new').select2({
            width: '100%',
           dropdownParent: $("#addcustomer_modal").length ? $("#addcustomer_modal") : $("#updatecustomer_modal")
        })
    </script>
    <script>
          $(document).on('change', '#category_add', function() {
            var category_id = $(this).val();
            console.log(category_id)
            $.ajax({
                method: "GET",
                url: '{{ route('pos.filter') }}',
                data: {
                    category_id: category_id
                },
                success: function(response) {
                    if (response) {
                        $('.product-list').html(response);
                    } else {
                        $('.product-list').html(`
                        <div class="text-center">@lang('Product Not Found')</div>
                    `);
                    }
                }
            })
        });
        //search product
        $(document).on('keyup', '#search_product', function() {
            var search_product = $(this).val();
            if (search_product.length > 2) {
                $.ajax({
                    method: "GET",
                    url: '{{ route('pos.filter.ajax') }}',
                    data: {
                        search_product: search_product
                    },
                    success: function(response) {
                        if (response) {
                            $('.product-list').html(response);
                        } else {
                            $('.product-list').html(`
                        <div class="text-center">@lang('Product Not Found')</div>
                    `);
                        }
                    }
                })
            } else {
                $.ajax({
                    method: "GET",
                    url: '{{ route('pos.filter.ajax') }}',
                    success: function(response) {
                        if (response) {
                            $('.product-list').html(response);
                        } else {
                            $('.product-list').html(`
                        <div class="text-center">@lang('Product Not Found')</div>
                    `);
                        }
                    }
                })
            }
        });
    </script>
</body>

</html>