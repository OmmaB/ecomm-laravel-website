<!DOCTYPE html>
<html>
<head>
  <title>Order @if($order)- {{$order->order_number}} @endif</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

@if($order)
<style type="text/css">
  .invoice-header {
    background: #f7f7f7;
    padding: 10px 20px 10px 20px;
    border-bottom: 1px solid purple;
  }
  .site-logo {
    margin-top: 20px;
  }
  .invoice-right-top h3 {
    padding-right: 20px;
    margin-top: 20px;
    color: purple;
    font-size: 30px!important;
    font-family: serif;
  }
  .invoice-left-top {
    border-left: 4px solid purple;
    padding-left: 20px;
    padding-top: 20px;
  }
  .invoice-left-top p {
    margin: 0;
    line-height: 20px;
    font-size: 16px;
    margin-bottom: 3px;
  }
  thead {
    background: purple;
    color: #FFF;
  }
  .authority h5 {
    margin-top: -10px;
    color: purple;
  }
  .thanks h4 {
    color: purple;
    font-size: 25px;
    font-weight: normal;
    font-family: serif;
    margin-top: 20px;
  }
  .site-address p {
    line-height: 6px;
    font-weight: 300;
  }
  .table tfoot .empty {
    border: none;
  }
  .table-bordered {
    border: none;
  }
  .table-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0,0,0,.03);
    border-bottom: 1px solid rgba(0,0,0,.125);
  }
  .table td, .table th {
    padding: .30rem;
  }
</style>
  <div class="invoice-header">
    <!--div class="float-left site-logo">
      <--a href="index.html"><img src="{{ asset('storage/photos/logo.png') }}" alt="#"></a>             
    </div-->
    <div class="float-center site-address">
      <h4>{{env('title','KOR-SHOP')}}</h4>
      <p> Address: NO. 342 - belvedere, Casablanca Morocco</p>
      <p> Phone: +212 60 982 55 21</p>
      <p> Email: kors_shop@gmail.com</p>
      <!--p>Phone: <a href="tel:{{env('SETTINGS_PHONE')}}">{{env('SETTINGS_PHONE')}}</a></p-->
      <!--p>Email: <a href="mailto:{{env('SETTINGS_EMAIL')}}">{{env('SETTINGS_EMAIL')}}</a></p-->
    </div>
    <div class="clearfix"></div>
  </div>
  <div class="invoice-description">
    <div class="invoice-left-top float-left">
      <h6>Invoice to</h6>
       <h3>{{$order->first_name}} {{$order->last_name}}</h3>
       <div class="address">
        <p>
          <strong>Country: </strong>
          {{$order->country}}
        </p>
        <p>
          <strong>Address: </strong>
          {{ $order->address1 }} OR {{ $order->address2}}
        </p>
         <p><strong>Phone:</strong> {{ $order->phone }}</p>
         <p><strong>Email:</strong> {{ $order->email }}</p>
       </div>
    </div>
    <div class="invoice-right-top float-right" class="text-right">
      <h3>Invoice #{{$order->order_number}}</h3>
      <p>{{ $order->created_at->format('D d m Y') }}</p>
      {{-- <img class="img-responsive" src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(150)->generate(route('admin.product.order.show', $order->id )))}}"> --}}
    </div>
    <div class="clearfix"></div>
  </div>
  <section class="order_details pt-3">
    <div class="table-header">
      <h5>Order Details</h5>
    </div>
    <table class="table table-bordered table-stripe">
      <thead>
        <tr>
          <th scope="col" class="col-6">Product</th>
          <th scope="col" class="col-3">Quantity</th>
          <th scope="col" class="col-3">Total</th>
        </tr>
      </thead>
      <tbody>
      @foreach($order->cart_info as $cart)
      @php 
        $product=DB::table('products')->select('title')->where('id',$cart->product_id)->get();
      @endphp
        <tr>
          <td><span>
              @foreach($product as $pro)
                {{$pro->title}}
              @endforeach
            </span></td>
          <td>x{{$cart->quantity}}</td>
          <td><span>${{number_format($cart->price,2)}}</span></td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
        <!--tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right">Subtotal:</th>
          <th scope="col"> <span>${{number_format($order->sub_total,2)}}</span></th>
        </tr-->
        @php
        $taxRate = 0.20;
        $subtotal = $order->sub_total;
        $taxAmount = $subtotal * $taxRate;
        $totalWithTax = $subtotal + $taxAmount;
        @endphp

<tr>
  <th scope="col" class="empty"></th>
  <th scope="col" class="text-right">Subtotal:</th>
  <th scope="col"><span>${{number_format($subtotal, 2)}}</span></th>
</tr>
<tr>
  <th scope="col" class="empty"></th>
  <th scope="col" class="text-right">TVA ({{ $taxRate * 100 }}%):</th>
  <th scope="col"><span>${{number_format($taxAmount, 2)}}</span></th>
</tr>
<tr>
  <th scope="col" class="empty"></th>
  <th scope="col" class="text-right">Total TTC:</th>
  <th scope="col"><span>${{number_format($totalWithTax, 2)}}</span></th>
</tr>

      {{-- @if(!empty($order->coupon))
        <tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right">Discount:</th>
          <th scope="col"><span>-{{$order->coupon->discount(Helper::orderPrice($order->id, $order->user->id))}}{{Helper::base_currency()}}</span></th>
        </tr>
      @endif --}}
        <tr>
          <th scope="col" class="empty"></th>
          @php
            $shipping_charge=DB::table('shippings')->where('id',$order->shipping_id)->pluck('price');
          @endphp
          <th scope="col" class="text-right ">Shipping:</th>
          <th><span>${{number_format($shipping_charge[0],2)}}</span></th>
        </tr>
        <tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right">Total:</th>
          <th>
            <span>${{number_format($totalWithTax, 2)}}</span>
          </th>
        </tr>
      </tfoot>
    </table>
  </section>
  <div class="thanks mt-3">
    <h4>Thank you for your visit, See you soon !!</h4>
  </div>
  <div class="authority float-right mt-5">
    <p>-----------------------------------</p>
    <h5>Authority Signature:</h5>
  </div>
  <div class="clearfix"></div>
@else
  <h5 class="text-danger">Invalid</h5>
@endif
</body>
</html>