@extends('layout')
  
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Welcome to your Profile') }}</div>
  
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
  
                    <table style="width:100%; padding:40px 15px;background: #ffffff">
                            <tbody>

                            <tr>
                                <td style="width:100%;font-weight:inherit; font-size:12px;color: #000000; padding:5px 0px 15px;text-align: left">
                                    Name : {{$client->name}}
                                </td>
                            </tr>

                            <tr>
                                <td style="width:100%;font-weight:inherit; font-size:12px;color: #000000; padding:5px 0px 15px;text-align: left">
                                    Email : {{$client->email}}
                                </td>
                            </tr>
                        
                            <tr>
                                <td style="width:100%;font-weight:inherit; font-size:12px;color: #000000; padding:5px 0px 15px;text-align: left">
                                    Phone NUmber : {{$client->phone_number}}
                                </td>
                            </tr>

                            <tr>
                                <td style="width:100%;font-weight:inherit; font-size:12px;color: #000000; padding:5px 0px 15px;text-align: left">
                                    Latitude : {{$client->cityLocation->city_location}}
                                </td>
                            </tr>
                      
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection