<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendOrderEmail;
use App\Mail\OrderShipped;
use App\Order;

//use Log;


class MailController extends Controller
{
    public function index(){
        try{
            Log::info('Sending order notification');
            $order=Order::findOrFail(rand(1,50));
            SendOrderEmail::dispatch($order)->onQueue('email');
            
        }catch(Exception $e){
            Log::error('An error occurred: ', $e);
        }
        
        Log::info('Dispatched order ' . $order->id);
        return 'Sent order ' . $order->id;
    }
}
