<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requests;
use App\Answers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\mail\RequestCreatedMail;
use App\mail\RequestClosedMail;
use App\User;

class RequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $requests = new Requests;

        if($request['sortStatus'])
        {
            $requests = $requests->orderBy('status', $request['sortStatus']);
        }
        
        if($request['sortIsRead'])
        {
            $requests = $requests->orderBy('is_read', $request['sortIsRead']);
        }
       
        if($request['status'])
        {
            if($request['status'] ==3) $request['status'] = 0;
            $requests = $requests->where('status', $request['status']);
        }
       
        if($request['is_read'])
        {
            if($request['is_read'] ==2) $request['is_read'] = 0;
            $requests = $requests->where('is_read', $request['is_read']);
        }

        
        $userRoles = Auth::user()->roles->pluck('name');

        if($userRoles->contains('client')){

            $requests = $requests->where('client_id', Auth::id())->orderBy('id', 'desc')->with(['closed', 'client'])->paginate(10)->appends([
                                    'sortStatus' => $request['sortStatus'], 
                                    'sortIsRead' => $request['sortIsRead'], 
                                    'status' => $request['status'], 
                                    'is_read' => $request['is_read'], 
                                ]);

        } else {

            $requests = $requests->orderBy('id', 'desc')->with(['closed', 'client'])->paginate(10)->appends([
                                    'sortStatus' => $request['sortStatus'], 
                                    'sortIsRead' => $request['sortIsRead'], 
                                    'status' => $request['status'], 
                                    'is_read' => $request['is_read'], 
                                ]);

        }

        return view('request')->with(['requests' => $requests]);
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
        // KODI DLYA SOZDAT NOVIY ZAPROS

        // validatsiya
        $request->validate([
                'title' => 'required|min:2|max:200',
                'message' => 'required|min:2|max:500',
                'file' => 'required|mimes:doc,docx,pdf,txt,xlx,jpg,jpeg,png,csv|max:2048',   
            ]);

        // файл загружен
        if($request->hasFile('file')) {

            $uploadedFile = $request->file('file');  //input name
            $filename = uniqid().'.'.$uploadedFile->getClientOriginalExtension(); // generate new file name

            $request->file->move('uploads/', $filename);
        }

        try {
            $newRequest = Requests::create([
                    'title' => $request['title'],
                    'message' => $request['message'],
                    'file' => $filename,
                    'client_id' => Auth::id(),
            ]);

            $details = [
                'title' => $request['title'],
                'message' => $request['message'],
                'request_id' => $newRequest->id,
                'file' => $filename,
                'client_id' => Auth::id(),
                'client_name' => Auth::user()->name,
            ];

            // Электронная почта менеджера
            $user = User::where('id', 1)->first();
            $user = $user->toArray();
            
            //Отправить письмо менеджеру
            Mail::to($user['email'])->send(new RequestCreatedMail($details));

            return back()->withSuccess('Ваш запрос был успешно отправлен!');

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
        // POKAZAT ZAPROS
        $userRoles = Auth::user()->roles->pluck('name');
        $request = Requests::with(['closed', 'client'])->findOrFail($id);

        if($request->is_read == 0 && $userRoles->contains('manager'))
        {
            $request->is_read = 1;
            $request->update();
        }

        $answers = Answers::where('request_id', $id)->with('user')->get();

        return view('singlerequest')->with([
                                            'request' => $request,
                                            'answers' => $answers,
                                           ]);
    }

    public function close($id)
    {
        // ZAKRIT ZAPROS
        $userRoles = Auth::user()->roles->pluck('name');
        $request = Requests::with(['client'])->findOrFail($id);
        
        // TOLKA MENEGER ILI KLENT(kotoriy sozdal zapros) MOJET ZAKRIVAY
        if($userRoles->contains('manager') || $request->client_id == Auth::id())
        {
            $request->status = 1; 
            $request->closed_by = Auth::id();
            $request->save();

            $request = $request->toArray();

            // Отправить письмо manager/client
            if(!$userRoles->contains('manager'))
            {
                // Электронная почта менеджера
                $user = User::where('id', 1)->first();
                $user = $user->toArray();
                $email = $user['email'];
                $name = $user['name'];

            } else {

                $email = $request['client']['email'];
                $name = $request['client']['name'];
            }

            $details = [
                'request_id' => $id,
                'name' => $name,
            ];

            Mail::to($email)->send(new RequestClosedMail($details));


            return back()->withSuccess('запрос был успешно закрыт!');
        }
        
        return view('nopermission');

    }

    public function accept($id)
    {
        // KODI DLYA MENEGER PRONYAT ZAPROS
        $userRoles = Auth::user()->roles->pluck('name');
        if(!$userRoles->contains('manager'))
        {
            return view('nopermission');
        }

        $request = Requests::findOrFail($id);

        $request->status = 2; 
        $request->save();
        return back()->withSuccess('Запрос был успешно принят!');
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
