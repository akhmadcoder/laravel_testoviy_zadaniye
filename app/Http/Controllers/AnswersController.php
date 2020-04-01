<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answers;
use App\Requests;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\mail\RequestHasNewMail;

class AnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // KOD DLYA OTPRAVIT SOOBSHENIYU
        // validatsiya
        $request->validate([
                'message' => 'required|min:2|max:500',
            ]);

        try {

            Answers::create([
                    'request_id' => $request['request_id'],
                    'message' => $request['message'],
                    'answered_by' => Auth::id(),
            ]);

            // OTPRAVIT EMAIL MANAGER ILI KLENTA
            $userRoles = Auth::user()->roles->pluck('name');
            if(!$userRoles->contains('manager'))
            {
                // Электронная почта менеджера
                $user = User::where('id', 1)->first();
                $user = $user->toArray();
                $email = $user['email'];

            } else {

                $email = $request['client_email'];
            }

            $details = [
                'message' => $request['message'],
                'request_id' => $request['request_id'],
            ];

            Mail::to($email)->send(new RequestHasNewMail($details));
 
            return back()->withSuccess('Сообщение было успешно отправлено!');

        } catch (Exception $e) {

            return back()->withFail('Что-то не так! Пожалуйста, попробуйте еще раз.');
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
