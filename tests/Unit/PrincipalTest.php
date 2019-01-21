<?php

namespace Tests\Unit;
namespace Config;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use File;
use Schema;

class PrincipalTest extends TestCase
{

    public function testGitConfig()
    {
        // Verifica se existe um usuário e senha registrado no arquivo de configuração

        $this->assertNotEmpty(config('app.keys.usname'));
        $this->assertTrue(is_string(config('app.keys.usname')));

        $this->assertNotEmpty(config('app.keys.passw'));
        $this->assertTrue(is_string(config('app.keys.passw')));

        // Verifica se a API do Github está disponível
        $response = $this->get('http://api.github.com');
        //$response->assertStatus(200);
    }

    public function testRepoConfig()
    {
        // Verifica se as 5 linguagens estão setadas no arquivo de configuração
        $this->assertEquals(5, count(config('app.keys.lng')));
    }

    public function testDiretory()
    {
        // Verifica se os diretórios dos repositórios estão criados
        // Verifica se os diretórios tem as permissões necessárias

        $dirs =  config('app.keys.lng');
        foreach($dirs as $dir)
        {
            $this->assertTrue(File::isDirectory(public_path() . '/uploads/' . $dir));
            $this->assertTrue(File::isReadable(public_path() . '/uploads/' . $dir));
            $this->assertTrue(File::isWritable(public_path() . '/uploads/' . $dir));
        }
    }

    public function testDBConnection()
    {
        if(Schema::hasTable('repositories'))
        {
            dd('morre aqui');
        }

    }

}
