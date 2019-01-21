<a href="#" class="repo-link" data-ref="{{ $repo->REP_ID }}" data-path="{{ $repo->REP_PATH  }}">
<div class="repoblock-box">
    <div class="row">
        <div class="col-2">
            <img src="{{ asset('/img/'.$repo->REP_LANG.'.png')  }}" alt="" class="repo-photo" style="border-radius: 50%;">
        </div>
        <div class="col-10">
            <h3>{{ $repo->REP_NAME }}</h3>
            <p>por <img src="{{ asset('/img/user.png')  }}" alt="{{ $repo->REP_AUTHOR }}" style="border-radius: 50%;" > <i>{{ $repo->REP_AUTHOR }}</i></p>
            <p class="desc">{{ $repo->REP_DESC }}</p>

            <div class="gitbuttons">
                <span class="gitbutton gitbutton-left"><i class="fa fa-star"></i> Star</span><span class="gitbutton gitbutton-right">{{ $repo->REP_STARS }}</span>
                <span class="gitbutton gitbutton-left"><i class="fa fa-code-fork"></i> Forks</span><span class="gitbutton gitbutton-right">{{ $repo->REP_FORKS }}</span>
                <button onclick="document.location.href = '{{ asset('uploads/'.$repo->REP_LANG.'/'.$repo->REP_AUTHOR.'-'.$repo->REP_NAME.'.zip') }}'; return false" class="btn btn-sm btn-success btndownload" ><i class="fa fa-download"></i> Download</button>
            </div>

           <!-- <a href="{{ asset('uploads/'.$repo->REP_LANG.'/'.$repo->REP_AUTHOR.'-'.$repo->REP_NAME.'.zip') }}" class="btn btn-sm btn-success" target="_blank" ><i class="fa fa-download"></i> Download</a> -->

        </div>
    </div>
</div>
</a>