 <ul id="transectionall">
    @php
        $uniqueTransactions = array_unique($transactions->pluck('transaction_no')->toArray());
    @endphp
    @foreach($uniqueTransactions as $transactionNo)
        <li>
            <div class="transection" data-transaction_no="{{$transactionNo}}" data-amount="">
                {{ __($transactionNo) }}
            </div>
        </li>
    @endforeach
</ul>
