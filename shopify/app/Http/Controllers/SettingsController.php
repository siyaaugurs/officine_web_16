<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Session;

class SettingsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $settings
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $settings)
    {
        $setting = $settings->find(1);
        return view('settings.show', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->except('_token');
        
        Setting::updateOrCreate(['id' => 1], $data);
        Session::flash('notice', 'Settings updated successfully.');
        return redirect()->back();
    }
}
