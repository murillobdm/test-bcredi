@extends('principal.painel')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-6">

                <div class="maintitle" >
                    <p><a href="#" id="updateRepo" class="btn btn-danger"><i class="fa fa-code-fork"></i> Atualizar repositórios</a><p>
                    <p></p>
                </div>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#yaml" role="tab" aria-controls="yaml" aria-selected="true">Yaml</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#php" role="tab" aria-controls="php" aria-selected="false">PHP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="messages-tab" data-toggle="tab" href="#html" role="tab" aria-controls="html" aria-selected="false">HTML</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="settings-tab" data-toggle="tab" href="#lua" role="tab" aria-controls="lua" aria-selected="false">Lua</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="settings-tab" data-toggle="tab" href="#python" role="tab" aria-controls="python" aria-selected="false">Python</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div id="content-repos" class="tab-content">
                    <div class="tab-pane active" id="yaml" role="tabpanel" aria-labelledby="yaml-tab">
                        @foreach ($data[0] as $yaml)
                            @include('partials.repoblock', ['repo' => $yaml])
                        @endforeach
                    </div>
                    <div class="tab-pane" id="php" role="tabpanel" aria-labelledby="php-tab">
                        @foreach ($data[1] as $php)
                            @include('partials.repoblock', ['repo' => $php])
                        @endforeach
                    </div>
                    <div class="tab-pane" id="html" role="tabpanel" aria-labelledby="html-tab">
                        @foreach ($data[2] as $html)
                            @include('partials.repoblock', ['repo' => $html])
                        @endforeach
                    </div>
                    <div class="tab-pane" id="lua" role="tabpanel" aria-labelledby="lua-tab">
                        @foreach ($data[3] as $lua)
                            @include('partials.repoblock', ['repo' => $lua])
                        @endforeach
                    </div>
                    <div class="tab-pane" id="python" role="tabpanel" aria-labelledby="python-tab">
                        @foreach ($data[4] as $python)
                            @include('partials.repoblock', ['repo' => $python])
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div >
                    <p class="lastupdade"><b>Última atualização: </b> {{ $data[0][1]->updated_at->format('d/m/Y \à\s H:m')  }}</p>
                </div>

                <div class="code-repos">
                    <p id="thepath">Arquivos:</p>
                    <div id="thecode" class="code-box">
                        <p class="text-center apresentacao" >Veja a lista de arquivos aqui</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
