<?php

namespace App\Http\Controllers\Repositorio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositorio;
use App\Models\Node;
use Carbon\Carbon;

class RepositorioController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\repositorio  $repositorio
     * @return \Illuminate\Http\Response
     */
    public function show(repositorio $repositorio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\repositorio  $repositorio
     * @return \Illuminate\Http\Response
     */
    public function edit(repositorio $repositorio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\repositorio  $repositorio
     * @return \Illuminate\Http\Response
     */
    public function update($linguagem)
    {
        $now = Carbon::now('utc')->toDateTimeString();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://github-trending-api.now.sh/repositories?language=".$linguagem."&since=daily");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response);

        $new = [
            ['REP_LANG' => $linguagem, 'REP_ORDER' => 1, 'REP_NAME' => $data[0]->name, 'REP_AUTHOR' => $data[0]->author, 'REP_URL' => $data[0]->url, 'REP_DESC' => $data[0]->description, 'REP_STARS' => $data[0]->stars, 'REP_FORKS' => $data[0]->forks, 'REP_BUILTBY' => serialize($data[0]->builtBy), 'REP_TREE' => '', 'created_at' => $now, 'updated_at' => $now],
            ['REP_LANG' => $linguagem, 'REP_ORDER' => 2, 'REP_NAME' => $data[1]->name, 'REP_AUTHOR' => $data[1]->author, 'REP_URL' => $data[1]->url, 'REP_DESC' => $data[1]->description, 'REP_STARS' => $data[1]->stars, 'REP_FORKS' => $data[1]->forks, 'REP_BUILTBY' => serialize($data[1]->builtBy), 'REP_TREE' => '', 'created_at' => $now, 'updated_at' => $now],
            ['REP_LANG' => $linguagem, 'REP_ORDER' => 3, 'REP_NAME' => $data[2]->name, 'REP_AUTHOR' => $data[2]->author, 'REP_URL' => $data[2]->url, 'REP_DESC' => $data[2]->description, 'REP_STARS' => $data[2]->stars, 'REP_FORKS' => $data[2]->forks, 'REP_BUILTBY' => serialize($data[2]->builtBy), 'REP_TREE' => '', 'created_at' => $now, 'updated_at' => $now],
            ['REP_LANG' => $linguagem, 'REP_ORDER' => 4, 'REP_NAME' => $data[3]->name, 'REP_AUTHOR' => $data[3]->author, 'REP_URL' => $data[3]->url, 'REP_DESC' => $data[3]->description, 'REP_STARS' => $data[3]->stars, 'REP_FORKS' => $data[3]->forks, 'REP_BUILTBY' => serialize($data[3]->builtBy), 'REP_TREE' => '', 'created_at' => $now, 'updated_at' => $now],
            ['REP_LANG' => $linguagem, 'REP_ORDER' => 5, 'REP_NAME' => $data[4]->name, 'REP_AUTHOR' => $data[4]->author, 'REP_URL' => $data[4]->url, 'REP_DESC' => $data[4]->description, 'REP_STARS' => $data[4]->stars, 'REP_FORKS' => $data[4]->forks, 'REP_BUILTBY' => serialize($data[4]->builtBy), 'REP_TREE' => '', 'created_at' => $now, 'updated_at' => $now]
        ];

        $oldRepo = Repositorio::where('REP_LANG', '=', $linguagem);

        if($oldRepo)
            $oldRepo->delete();

        Repositorio::insert($new);
        error_log('UPDADE: '.$linguagem);

        foreach($new as $el)
        {
            error_log('[START] Expand: ['.$linguagem.'] from '.$el['REP_NAME'].'/'.$el['REP_AUTHOR']);
            $this->maketree($el['REP_LANG'], $el['REP_NAME'], $el['REP_AUTHOR']);
            error_log('[STOP] Expand: ['.$linguagem.'] from '.$el['REP_NAME'].'/'.$el['REP_AUTHOR']);
        }

        return '';
    }

    public function bulkUpdate(Request $request)
    {
        set_time_limit(0);
        dd(config('app.keys.lng'));

        $linguagens = config('app.keys.lng');
        foreach($linguagens as $lg)
        {
            $this->update($lg);
        }

        $response_array['status'] = 'success';
        header('Content-type: application/json');
        echo json_encode($response_array);
    }

    public function bulkDownload(Request $request)
    {
        set_time_limit(0);

        $linguagens = config('app.keys.lng');
        foreach($linguagens as $lg)
        {
            $this->download($lg);
        }

        $response_array['status'] = 'success';
        header('Content-type: application/json');
        echo json_encode($response_array);
    }

    public function download($linguagem)
    {
        $repos = Repositorio::where('REP_LANG',$linguagem)->get();

        foreach($repos as $re)
        {
            error_log('[START] Download: '.$re->REP_NAME.' from '.$re->REP_AUTHOR.' - '.$linguagem);

            $url = "https://github.com/".$re->REP_AUTHOR."/".$re->REP_NAME."/archive/master.zip";
            $path = "../public/uploads/".$linguagem."/".$re->REP_AUTHOR."-".$re->REP_NAME.".zip";

            $fp = fopen($path, 'w');

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $data = curl_exec($ch);

            curl_close($ch);
            fclose($fp);

            error_log('[END] Download: '.$re->REP_NAME.' from '.$re->REP_AUTHOR.' - '.$linguagem);
        }

        return true;
    }

    public function maketree($linguagem,$repo_nome, $repo_autor)
    {
        $tree[] = new Node('root', '', '', array());

        $tree[0]->content = $this->expandNode($tree[0], $repo_autor, $repo_nome);

        if(Repositorio::where('REP_NAME', $repo_nome)->where('REP_AUTHOR', $repo_autor)->where('REP_LANG', $linguagem)->update(['REP_TREE' => json_encode($tree)]))
        {
            sleep(3);
            return true;
        }

        return false;
    }

    public function expandNode($no, $repo_autor, $repo_nome)
    {
        $getData = $this->requestCurl($no->path, $repo_autor, $repo_nome);

        foreach($getData as $item)
        {

            array_push($no->content, new Node($item->name, $item->path, $item->type, array()));
        }

        foreach($no->content as $newno)
        {
            if($newno->type == 'dir')
            {
                $newno->content = $this->expandNode($newno, $repo_autor, $repo_nome);
            }
            else
            {
                $newno->content = null;
            }
        }

        return $no->content;
    }

    public function requestCurl($path, $repo_autor, $repo_nome)
    {
        set_time_limit(0);
        $url = "https://api.github.com/repos/" . $repo_autor . "/" . $repo_nome . "/contents/".$path;
        $url = str_replace(' ', '%20', $url);

        error_log(' -- '.$url);

        $user = config('app.keys.usname');
        $pwd = config('app.keys.passw');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $user . ':' . $pwd);

        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response);

        return $json;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\repositorio  $repositorio
     * @return \Illuminate\Http\Response
     */
    public function destroy(repositorio $repositorio)
    {
        //
    }
}
