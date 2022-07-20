<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
//        $this->authorize('view', Slider::class);

        $slider = Slider::orderBy('id', 'desc')->get();

        return view('admin.pages.slider.index', [
            'slider' => $slider]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
//        $this->authorize('create', Slider::class);

        $Sliders = Slider::orderBy('id', 'desc')->get();
        return view('admin.pages.slider.create',['Sliders' => $Sliders]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $Slider = new Slider;
        $Slider->title = $request->input('title');
        $Slider->subtitle = $request->input('subtitle');
        $Slider->description = $request->input('description');
        $Slider->link = $request->input('link');

        // image
        if($request->hasfile('thumbnail'))
        {

            $file = $request->file('thumbnail');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('storage/slider/', $filename);
            $Slider->thumbnail = $filename;
        }

        $Slider->save();
        return redirect()->route('slider.index')->with('message','Slider Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $slider = Slider::where('id',$id)->first();

        return view('admin.pages.slider.show',['slider' => $slider]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $slider = Slider::find($id);
        return view('admin.pages.slider.edit', ['slider' => $slider]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $Slider = Slider::find($id);
        $Slider->title = $request->input('title');
        $Slider->subtitle = $request->input('subtitle');
        $Slider->description = $request->input('description');
        $Slider->link = $request->input('link');
        // image
        if($request->hasfile('thumbnail'))
        {
            $destination = 'storage/slider/'.$Slider->thumbnail;
            if(File::exists($destination))
            {
                File::delete($destination);
            }
            $file = $request->file('thumbnail');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('storage/slider/', $filename);
            $Slider->thumbnail = $filename;
        }
        $Slider->update();
        return redirect()->route('slider.index')->with(['message' => 'Slider Update successfully!', 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $Slider = Slider::find($id);
        //img
        $destination = 'public/slider/'.$Slider->thumbnail;
        if(File::exists($destination))
        {
            File::delete($destination);
        }
        $Slider->delete();
        return redirect()->route('slider.index')->with('message','Slider Has Been Deleted Successfully');
    }

}
