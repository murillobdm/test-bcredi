<?php

namespace App\Http\Controllers\Repositorio;

use Github\Api\Repo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositorio;
use App\Models\Node;
use Carbon\Carbon;
use GrahamCampbell\GitHub\Facades\GitHub;
use PhpParser\Node\Expr\Cast\Object_;
use File;

class RepositorioController extends Controller
{

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
            ['REP_LANG' => $linguagem, 'REP_ORDER' => 1, 'REP_NAME' => $data[0]->name, 'REP_AUTHOR' => $data[0]->author, 'REP_URL' => $data[0]->url, 'REP_DESC' => $data[0]->description, 'REP_STARS' => $data[0]->stars, 'REP_FORKS' => $data[0]->forks, 'REP_BUILTBY' => serialize($data[0]->builtBy), 'REP_TREE' => '', 'created_at' => $now, 'updated_at' => $now, 'REP_SHA' => ''],
            ['REP_LANG' => $linguagem, 'REP_ORDER' => 2, 'REP_NAME' => $data[1]->name, 'REP_AUTHOR' => $data[1]->author, 'REP_URL' => $data[1]->url, 'REP_DESC' => $data[1]->description, 'REP_STARS' => $data[1]->stars, 'REP_FORKS' => $data[1]->forks, 'REP_BUILTBY' => serialize($data[1]->builtBy), 'REP_TREE' => '', 'created_at' => $now, 'updated_at' => $now, 'REP_SHA' => ''],
            ['REP_LANG' => $linguagem, 'REP_ORDER' => 3, 'REP_NAME' => $data[2]->name, 'REP_AUTHOR' => $data[2]->author, 'REP_URL' => $data[2]->url, 'REP_DESC' => $data[2]->description, 'REP_STARS' => $data[2]->stars, 'REP_FORKS' => $data[2]->forks, 'REP_BUILTBY' => serialize($data[2]->builtBy), 'REP_TREE' => '', 'created_at' => $now, 'updated_at' => $now, 'REP_SHA' => ''],
            ['REP_LANG' => $linguagem, 'REP_ORDER' => 4, 'REP_NAME' => $data[3]->name, 'REP_AUTHOR' => $data[3]->author, 'REP_URL' => $data[3]->url, 'REP_DESC' => $data[3]->description, 'REP_STARS' => $data[3]->stars, 'REP_FORKS' => $data[3]->forks, 'REP_BUILTBY' => serialize($data[3]->builtBy), 'REP_TREE' => '', 'created_at' => $now, 'updated_at' => $now, 'REP_SHA' => ''],
            ['REP_LANG' => $linguagem, 'REP_ORDER' => 5, 'REP_NAME' => $data[4]->name, 'REP_AUTHOR' => $data[4]->author, 'REP_URL' => $data[4]->url, 'REP_DESC' => $data[4]->description, 'REP_STARS' => $data[4]->stars, 'REP_FORKS' => $data[4]->forks, 'REP_BUILTBY' => serialize($data[4]->builtBy), 'REP_TREE' => '', 'created_at' => $now, 'updated_at' => $now, 'REP_SHA' => '']
        ];

        $oldRepo = Repositorio::where('REP_LANG', $linguagem)->get();

        // Verifica se já existem registros
        if(count($oldRepo) > 0)
        {   
            // Existem registros
            foreach($new as $n)
            {

                // Atualiza posições
                $element = Repositorio::where('REP_NAME', $n['REP_NAME'])->firstOrFail();

                if($element != null)
                {
                    // Elemento existe e posição é atualizada
                    $query = Repositorio::where('REP_NAME', $n['REP_NAME'])
                        ->where('REP_LANG', $linguagem)
                        ->update(['REP_ORDER' => $n['REP_ORDER']]);
                }
                else
                {
                    // Elemento não existe e é inserido na posição coletada
                    Repositorio::insert($n);
                }
            }

        }
        else
        {
            // Tabela Vazia
            Repositorio::insert($new);
            error_log('UPDADE: ' . $linguagem);
        }
        
        $new = Repositorio::where('REP_LANG', $linguagem)->get();
        
        foreach($new as $el)
        {
            $getData = GitHub::repo()->commits()->all($el['REP_AUTHOR'], $el['REP_NAME'], $params = array());
            $SHA = $getData[0]['sha'];

            if($SHA == $el['REP_SHA'])
            {
                error_log('[START] Expand: ['.$linguagem.'] from '.$el['REP_NAME'].'/'.$el['REP_AUTHOR']);
                error_log('Nothing to expand');
                error_log('[STOP] Expand: ['.$linguagem.'] from '.$el['REP_NAME'].'/'.$el['REP_AUTHOR']);
            }
            else
            {
                Repositorio::where('REP_NAME', $el['REP_NAME'])
                    ->where('REP_LANG', $linguagem)
                    ->update(['REP_SHA' => $SHA]);

                error_log('[START] Expand: ['.$linguagem.'] from '.$el['REP_NAME'].'/'.$el['REP_AUTHOR']);
                $this->maketree($el['REP_LANG'], $el['REP_NAME'], $el['REP_AUTHOR']);
                error_log('[STOP] Expand: ['.$linguagem.'] from '.$el['REP_NAME'].'/'.$el['REP_AUTHOR']);
            }

        }

        return true;
    }

    public function bulkUpdate(Request $request)
    {
        set_time_limit(0);

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

        $this->atualizarDiretorio();

        $response_array['status'] = 'success';
        header('Content-type: application/json');
        echo json_encode($response_array);
    }

    public function download($linguagem)
    {
        $repos = Repositorio::where('REP_LANG', $linguagem)->get();

        foreach($repos as $re)
        {
            $getData = GitHub::repo()->commits()->all($re->REP_AUTHOR, $re->REP_NAME, $params = array());
            $SHA = $getData[0]['sha'];

            if(strcmp($SHA, $re->REP_SHA) == 0)
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
            else
            {
                error_log('[START] Download: '.$re->REP_NAME.' from '.$re->REP_AUTHOR.' - '.$linguagem);
                error_log('Nothing to download');
                error_log('[END] Download: '.$re->REP_NAME.' from '.$re->REP_AUTHOR.' - '.$linguagem);
            }
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
        error_log('[WAITING] Expand: ["'.$no->path.'"]' );
        $getData = $this->requestCurl($no->path, $repo_autor, $repo_nome);


        foreach($getData as $item)
        {
            $item = (Object)$item;
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

        $json = (Object)GitHub::repo()->contents()->show($repo_autor, $repo_nome, $path);
        return $json;
    }


    public function atualizarDiretorio()
    {
        $repos = config('app.keys.lng');

        foreach($repos as $repo)
        {
            error_log('[START] Clean: '.$repo);

            $files = File::allfiles('uploads/' . $repo);

            $repolist = Repositorio::where('REP_LANG',$repo);

            $original_files = array();
            foreach($repolist as $rl)
            {
                array_push($original_files, $rl->REP_AUTHOR.'-'.$rl->REP_NAME);
            }

            foreach($files as $file)
            {
                $key = array_search($file->getRelativePathname(), $original_files);
                if($key === false)
                {
                    File::delete("uploads/" . $repo . "/" . $file->getRelativePathname());

                    error_log('[WAITING] Delete: '.$file->getRelativePathname());
                }
            }

            error_log('[STOP] Clean: '.$repo);

        }

        return true;
    }
}
