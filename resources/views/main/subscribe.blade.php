@extends('main/layout')
@section('content')
<section class="banner-background" style="min-height: 100vh;">
    <div class="container py-5 mt-5">
      <div class="card bg-white py-0 px-0 mt-1 my-5">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 col-xl-6">
                <div class="signup-clipart">
                  <div class="container-fluid ">

                    </div>
                  </div>
              </div>
              <div class="col-lg-6 col-xl-6 ">
                <div class="container mb-5">
                  <h1 class="mb-3 text-1"><strong class="text-bold">Subscription!</strong></h1>
                  <p>
                    select payment method to proceed. Or contact site admin if you to pay manually.
                    </p>
                    <p>
                      Subscription Fee: <strong>{{$fee->amount}}$</strong>
                    </p>
                    <div class="my-2">

    @if($message=Session::get('error'))
        <div class="alert alert-danger"><li>{{$message}}</li></div>
    @endif
    @if($errors->any())
    @foreach($errors->all() as $er)
        <div class="alert alert-danger"><li>{{$er}}</li></div>
    @endforeach
    @endif
                                </div>
                  <div  id="subscription">

                  </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</section>
@stop
@section('javascript')
<script src="https://www.paypal.com/sdk/js?client-id=YOUR_CLIENT_ID"></script>
@if($user && $fee)
<script>
let usd = {{$fee->amount}}
paypal.Buttons({
 createOrder: function(data, actions) {
   // This function sets up the details of the transaction, including the amount and line item details.
   return actions.order.create({
     purchase_units: [{
       amount: {
         value: usd
       }
     }]
   });
 },
 onApprove: function(data, actions) {
   // This function captures the funds from the transaction.
   return actions.order.capture().then(function(details) {
     // This function shows a transaction success message to your buyer.
     //alert('Transaction completed by ' + details.payer.name.given_name);
     $.ajax({
       type:'post',
       url: '{{url("")}}/user/signup/subscription',
       data: {
         "_token": "{{csrf_token()}}",
         "amount": usd,
         "id": {{$user->id}}
       },
       success: (res)=>{
         if(res == 'success')
         {
           window.location.href = "{{url('')}}/user/login"
         }
       },
       error: (res)=>{
         console.log(res)
         alert('something went wrong')
       }
     })
   });
 }
}).render('#subscription');
//This function displays Smart Payment Buttons on your web page.
</script>
@endif
@stop
