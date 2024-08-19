<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Report Print</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
	<style media="all">
        @page {
			margin: 0;
			padding:0;
		}
		body{
			font-size: 0.575rem !important;
            font-weight: normal;
			padding:0;
			margin:0;
		}
		.gry-color *,
		.gry-color{
			color:#000;
		}
		table{
			width: 100%;
		}
		table th{
			font-weight: normal;
		}
		table.padding th{
			padding: .25rem .7rem;
		}
		table.padding td{
			padding: .25rem .7rem;
		}
		table.sm-padding td{
			padding: .1rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #eceff4;
		}
		.text-left{
			text-align:left;
		}
		.text-right{
			text-align:right;
		}
	</style>
</head>
<body>
	<div>


		<div style="background: #eceff4;padding: 0px 20px;">
			<div style="font-size: 1.8rem;
			display: flex;
			justify-content: center">{{ get_setting('site_name')->value }}</div>
			<table>
				<tr>
					{{-- <td style="font-size: 1rem;" class="text-right strong">INVOICE</td> --}}
				</tr>
			</table>
			<table>

				<tr>
					<td style="text-align: center">{{ get_setting('business_address')->value }}</td>
				</tr>
				<tr>
					<td class="text-center" style="text-align: center">Phone: {{ get_setting('phone')->value }}</td>
				</tr>

			</table>
			<hr>
		</div>

		{{-- <div style="padding: 0px 20px;">
            <table>
				<tr><td class="strong small gry-color">Supplier Name: {{ $supplier->name }}</td></tr>
				<tr>
					<td class=" small"><span class="gry-color small">Invoice No :</span> <span class="strong">{{ $purchase->purchase_invoice }}</span></td>
				</tr>
				<tr>
					<td class=" small"><span class="gry-color small">Billing Date:</span> <span class=" strong">{{ date('d-m-Y', strtotime($purchase->billing_date)) }}</span></td>
				</tr>
			</table>
		</div> --}}

	    <div style="padding: 0px 20px;">
			<table class="padding text-left small border-bottom">
				<thead>
	                <tr class="gry-color" style="background: #eceff4;">
	                    <th width="10%" class="text-left" style="font-weight: 600;">Sl</th>
	                    <th width="20%" class="text-left" style="font-weight: 600;">Name</th>
	                    <th width="15%" class="text-left" style="font-weight: 600;">Phone</th>
	                    <th width="15%" class="text-left" style="font-weight: 600;">Address</th>
	                    {{-- <th width="15%" class="text-left" style="font-weight: 600;">Paid</th>
	                    <th width="15%" class="text-left" style="font-weight: 600;">Due</th>
	                    <th width="15%" class="text-left" style="font-weight: 600;">Total</th> --}}
	                </tr>
				</thead>
				<tbody class="strong">
	                @foreach($customers as $key => $customer)
	                    @php
                            $order_total = App\Models\Order::where('user_id', $customer->id)->sum('grand_total');
                            //  dd($order_total);
                            $order_paid = App\Models\Order::where('user_id', $customer->id)->sum('paid_amount');
                            $payment_paid = App\Models\OrderPayment::where('user_id', $customer->id)->sum('paid');
                            $paid_total = ($order_paid+$payment_paid);
                            $due_total = ($order_total-$paid_total);
                        @endphp
						<tr class="">
							<td>{{ $key+1}}</td>
							<td> {{ $customer->name ?? 'No Name' }} </td>
                            <td> {{ $customer->phone ?? 'No Phone Number' }} </td>
                            <td>
                                @php
                                    $address = App\Models\Address::where('user_id', $customer->id)->first();
                                    if($address){
                                        $district=App\Models\District::where('id',$address->district_id)->first();
                                        $upazilla=App\Models\Upazilla::where('id',$address->upazilla_id)->first();
                                    }
                                @endphp

                                {{ isset($customer->address) ? $customer->address . ',' : 'No address' }}
                                {{ isset($upazilla) && isset($upazilla->name_en) ? ucwords($upazilla->name_en) . ',' : '' }}
                                {{ isset($district) && isset($district->district_name_en) ? ucwords($district->district_name_en) : '' }}

                            </td>
						    {{-- <td> {{ $paid_total }} </td>
                            <td class=""> {{ $due_total }} </td>
                            <td class="currency"> {{ $order_total }} </td> --}}
						</tr>
					@endforeach
	            </tbody>
			</table>
		</div>

	    <div style="padding:0 1.5rem;">
			<div style="display: flex; font-size: 13px; justify-content: center; margin: 10px 0px;">Developed By : <a target="_blank" style="text-decoration: none;font-weight: 700; margin-left: 5px;" href="https://classicit.com.bd"> Classic IT</a>
			</div>
	    </div>
	</div>
</body>
</html>

<script>
    window.onload = function() {
        window.print();
    };
</script>
