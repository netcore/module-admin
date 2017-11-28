<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\Admin\WhitelistRequest;
use Modules\Admin\Models\IpWhitelist;

class WhitelistController extends Controller
{
    /**
     * @var string
     */
    private $viewNamespace = 'admin::whitelist';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $whitelists = IpWhitelist::all();

        return view($this->viewNamespace . '.index', compact('whitelists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view($this->viewNamespace . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  IpWhitelistRequest $request
     * @return Response
     */
    public function store(WhitelistRequest $request)
    {
        $whitelist = IpWhitelist::create($request->all());

        return redirect()->route('admin::whitelist.index')->withSuccess('Successfully created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  IpWhitelist $whitelist
     * @return Response
     */
    public function edit(IpWhitelist $whitelist)
    {
        return view($this->viewNamespace . '.edit', compact('whitelist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param WhitelistRequest $request
     * @param IpWhitelist      $whitelist
     * @return Response
     */
    public function update(WhitelistRequest $request, IpWhitelist $whitelist)
    {
        $whitelist->update($request->all());

        return back()->withSuccess('Successfully saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  IpWhitelist $whitelist
     * @return Response
     */
    public function destroy(IpWhitelist $whitelist)
    {
        $whitelist->delete();

        return response()->json([
            'state' => 'success'
        ]);
    }
}
