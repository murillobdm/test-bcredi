<?php

namespace App\Http\Controllers\Principal;

use App\Models\Repositorio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrincipalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $yaml = Repositorio::where('REP_LANG','yaml')->orderBy('REP_ORDER','asc')->get();
        $php = Repositorio::where('REP_LANG','php')->orderBy('REP_ORDER','asc')->get();
        $html = Repositorio::where('REP_LANG','html')->orderBy('REP_ORDER','asc')->get();
        $lua = Repositorio::where('REP_LANG','lua')->orderBy('REP_ORDER','asc')->get();
        $python = Repositorio::where('REP_LANG','python')->orderBy('REP_ORDER','asc')->get();

        $data = [$yaml, $php, $html, $lua, $python];

        return view('principal\conteudo', ['name' => 'Conteúdo', 'data' => $data]);
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
        //
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

    public function maketree($id, $path = null)
    {
        $repo = Repositorio::where('REP_ID', $id)->first();

        $tree = json_decode($repo->REP_TREE);

        $html = '';
        $lastpath = '';

        if(!$path)
        {
            $list = $tree[0]->content;
        }
        else
        {
            $pieces = explode("/", $path);
            $lastpath = $pieces;
            array_pop($lastpath);
            $lastpath = implode("/", $lastpath);

            $list = $tree[0]->content;

            foreach($pieces as $pe)
            {
                foreach($list as $ref)
                {
                    if($ref->name == $pe)
                    {
                        $list = $ref->content;
                    }
                }
            }

            $html .= "<p><a href='#' class='filelist-element folder repo-link' data-ref='".$repo->REP_ID."' data-path='' ><i class='fa fa-folder-o'></i> .</a></p>";
            $html .= "<p><a href='#' class='filelist-element folder repo-link' data-ref='".$repo->REP_ID."' data-path='".$lastpath."' ><i class='fa fa-folder-o'></i> ..</a></p>";
        }

        foreach($list as $element)
        {
            if($element->type =='dir')
            {
                $html .= "<p><a href='#' class='filelist-element folder repo-link' data-ref='".$repo->REP_ID."' data-path='".$element->path."' ><i class='fa fa-folder-o'></i> ".$element->name."</a></p>";
            }
            else
            {
                $html .= "<p><a href='https://raw.githubusercontent.com/".$repo->REP_AUTHOR."/".$repo->REP_NAME."/master/".$element->path."' class='filelist-element file' target='_blank' ><i class='fa fa-file-o'></i> ".$element->name."</a></p>";
            }
        }

        return $html;

    }

}
