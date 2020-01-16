<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ayuda_blog;
use App\ayuda_event;
use App\ayuda_blog_comment;

class ayuda_blogcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
         $result = \DB::table('ayuda_blog')
       ->join('ayuda_event', 'ayuda_event.id', '=', 'ayuda_blog.event_id')
     
       ->select('ayuda_event.ayuda_event_name', 'ayuda_blog.*')
       ->get();
     
      return view('Admin.ayuda_blog.index',compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
          $data= ayuda_event::paginate(5);
        //print_r($result);
        return view('Admin.ayuda_blog.create',compact('data'));
          //print_r($data->ayuda_event_name);
        // return view('Admin.ayuda_blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
           $request->validate([
            'image'=>['required','image','mimes:jpeg,png,jpg,gif|max:2048'],
            'blogheading'=>['required','string'],
             'short_desc'=>['required','string'],
              'long_desc'=>['required','string'],
               'added_by'=>['required','string'],
                'blog_date'=>['required'],
                 'first_slogn'=>['required','string'],
                  'second_slogn'=>['required','string'],
                   
        ]);

        $image=$request->file('image');
        $new_name=$request->input('heading').''.rand().'.'.$image->getClientOriginalExtension();
        $image->move(public_path("upload/images/projectarea/"),$new_name);
        $img_name="upload/images/projectarea/".''.$new_name;


        $projectarea=new ayuda_blog();
        $projectarea->image=$img_name;
        $projectarea->blogheading=$request->input('blogheading');
       $projectarea->short_desc=$request->input('short_desc');
        $projectarea->long_desc=$request->input('long_desc');
         $projectarea->added_by=$request->input('added_by');
          $projectarea->blog_date=$request->input('blog_date');
           $projectarea->first_slogn=$request->input('first_slogn');
            $projectarea->second_slogn=$request->input('second_slogn');
             $projectarea->event_id=$request->input('ayuda_event_name');
              $projectarea->main_page_disp=null;
              $projectarea->view=null;
                
        $projectarea->save();

        return redirect('admin/ayuda_blog');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
         $result = \DB::table('ayuda_blog')
       ->join('ayuda_event', 'ayuda_event.id', '=', 'ayuda_blog.event_id')
      -> where('ayuda_blog.id','=', $id)
       // ->where('class_id', '=', $student->class_id)
       ->select('ayuda_event.ayuda_event_name', 'ayuda_blog.*')
       ->get();
     
      return view('Admin.ayuda_blog.readmore',compact('result'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
           $result = ayuda_blog::findorfail($id);
           $event=ayuda_event::findorfail($result->event_id);
            $data=ayuda_event::paginate(5);
           // print_r($event->ayuda_event_name);
     return view('Admin.ayuda_blog.edit',compact('result' ,'event','data'));
      
      //return view('Admin.ayuda_blog.edit',compact('result'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id )
    {
          print_r($request->ayuda_event_name);
      $projectarea=ayuda_blog::findorfail($id);
     //  $projectarea1=ayuda_event::findorfail($ayuda_event_name);
        if($request->hasFile('image'))
        {
                $image=$request->file('image');
                $new_name=$request->input('blogheading').''.rand().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('Client/images/'),$new_name);
                $img_name="client/images/".''.$new_name;
                $projectarea->update([
                    'image'=>$img_name,
                ]);
        }

        $projectarea->update([
            'blogheading'=>$request->input('blogheading'),
             'short_desc'=>$request->input('short_desc'),
             'long_desc'=>$request->input('long_desc'),
              'first_slogn'=>$request->input('first_slogn'),
               'added_by'=>$request->input('added_by'),
               'second_slogn'=>$request->input('second_slogn'),
                'event_id'=>$request->input('ayuda_event_name'),
        ]);

        return redirect('admin\ayuda_blog');
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $projectarea=ayuda_blog::findorfail($id);
        $projectarea->delete();
        return redirect('admin\ayuda_blog');
    }
}
