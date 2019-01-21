<?php

namespace App\Http\Controllers\Principal;

use App\Models\Repositorio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrincipalController extends Controller
{
    
    public function index()
    {
        $lang = config('app.keys.lng');
        $data = array();
        foreach($lang as $lg)
        {
                $query = Repositorio::where('REP_LANG',$lg)->orderBy('REP_ORDER','asc')->get();
                if($query)
                {
                    array_push($data, $query);
                }
                else
                {
                    array_push($data, null);
                }
        }

        return view('principal\conteudo', ['name' => 'Conteúdo', 'data' => $data, 'lang' => $lang]);
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
