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

                    @foreach($lang as $i=>$lg)
                    <li class="nav-item">
                        @if($i == 0)
                            <a class="nav-link active" id="{{ $lg }}-tab" data-toggle="tab" href="#{{ $lg }}" role="tab" aria-controls="{{ $lg }}" aria-selected="true">{{ ucfirst(strtolower($lg)) }}</a>
                        @else
                            <a class="nav-link" id="{{ $lg }}-tab" data-toggle="tab" href="#{{ $lg }}" role="tab" aria-controls="{{ $lg }}" aria-selected="true">{{ ucfirst(strtolower($lg)) }}</a>
                        @endif
                    </li>
                    @endforeach

                </ul>

                <!-- Tab panes -->
                <div id="content-repos" class="tab-content">

                    @foreach($lang as $i=>$lg)

                        @if(count($data[$i]) > 0)

                            @if($i == 0)
                                <div class="tab-pane active" id="{{ $lg }}" role="tabpanel" aria-labelledby="{{ $lg }}-tab">
                            @else
                                <div class="tab-pane" id="{{ $lg }}" role="tabpanel" aria-labelledby="{{ $lg }}-tab">
                            @endif

                            @foreach ($data[$i] as $elem)
                                    @include('partials.repoblock', ['repo' => $elem])
                            @endforeach
                        @else
                            @if($i == 0)
                                <div class="tab-pane active" id="{{ $lg }}" role="tabpanel" aria-labelledby="{{ $lg }}-tab">
                            @else
                                <div class="tab-pane" id="{{ $lg }}" role="tabpanel" aria-labelledby="{{ $lg }}-tab">
                            @endif

                            <div class="repoblock-box non">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-center">Não há informação</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        </div>
                    @endforeach

                </div>
            </div>
            <div class="col-6">
                <div>
                    @isset($data[0][1])
                        <p class="lastupdade"><b>Última atualização: </b>{{ $data[0][1]->updated_at->format('d/m/Y \à\s H:m') }}</p>
                    @endisset
                    @empty($data[0][1])
                            <p class="lastupdade"><b>Última atualização: </b> Nenhuma </p>
                    @endempty
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
