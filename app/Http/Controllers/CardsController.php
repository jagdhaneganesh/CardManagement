<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use DataTables;
use Session;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\Cards;
class CardsController extends Controller
{
    public function __construct()
	{

	  $this->middleware('auth');            
	}
	public function index(Request $request)
	{
     	$cards=Cards::get();
     	return view('home',compact('cards'));
     	
	}
	public function createCard(Request $request)
	{
		return view('card.createcard');

	}
	public function cardStore(Request $request)
	{
		// dd($request->all());
     	 $request->validate([
        'name' => 'required',
        'email' => 'required',
        'shortdescription' => 'required',
        'contact' => 'required',
        'address' => 'required',
        ]);

        try {
        	$input = $request->except('_token');
        	$userExist = Cards::where('email', $input['email'])->first();
            if(empty($userExist)) {
                $input['name']     = $input['name'];
                $input['email']    = $input['email'];
                $input['description']= $input['shortdescription'];
            	$input['contacts'] = $input['contact'];
            	$input['address'] = $input['address'];
                $user = Cards::create($input);
                Session::flash('success', 'Card Created Successfully');
             return redirect('home');
            } else {
             Session::flash('error', 'Card already exists. Please try again');
                 return redirect('home');
             }
        } catch (Exception $e) {
            Session::flash('error', 'Some error while creating Customer. Please try again');
            return redirect('home');
        }
     	
	}

	public function deleteCard(Request $request) {
       $cardid = $request->id;
       if(!empty($cardid)){
       try {
            Cards::where('id', $cardid)->delete();
            echo true;
        }catch(\Exception $e) {
          echo false;
        }
        
        echo true;
        }else{
        echo false; 
        } 
    }

    public function edit($id)
  {
  	try {
            // $id = decrypt($request->uid);
            $card = Cards::where("id",$id)->first();
            
            return view('card.editcard', ['card'=>$card]);
            
        } catch (\Exception $e) {
        	dd($e);
        	return redirect('home');
        }
    
  }
  public function update(Request $request) 
  {
    try {
             
           // dd($request->all); 
           	$card = Cards::where('id', $request->cardid)->first();
		    $card->name = $request->name;
		    $card->email = $request->email;
		    $card->description = $request->shortdescription;
		    $card->contacts = $request->contact;
		    $card->address = $request->address;
		    $card->save();    
		    // $dataid = $card->id;
            // dd($user);
            Session::flash('success', 'Card Updated Successfully');
            return redirect('home');
        } catch (\Exception $e) {
        	dd($e);
            Session::flash('error', 'Something went wrong');
            return redirect('home');
        }
  }
   public function viewCard($id)
  {
  	try {
            $card = Cards::where("id",$id)->first();
            
            return view('card.view', ['card'=>$card]);
            
        } catch (\Exception $e) {
        	dd($e);
        	return redirect('home');
        }
    
  }

}
