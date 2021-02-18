@extends('layout.app')
@section('title','(`・ω・´)')
@section('maincontent')
<!-- BANNER -->
<section class="banner_sec">
    <div class="container">
        <div class="text-center">
            <h1 class="text-white">全部文章</h1>
        </div>
    </div>
</section>
<!-- BANNER -->

<!-- BODY -->
<div class="home_body">
    <div class="container">
        <div class="latest_post">
            <div class="latest_post_top">
                <h1 class="latest_post_h1 brdr_line">Latest Blog</h1>
            </div>
            <div class="row">
                @if (count($blogs)>0)
                @foreach ($blogs as $b)
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="/blog/{{$b->slug}}">
                        <div class="home_card">
                            <!-- <div class="home_card_top">
                                <img src="#" alt="image">
                            </div> -->
                            <div class="home_card_bottom">
                                <div class="home_card_bottom_text">
                                    @if (count($b->cat)>0)
                                    <ul class="home_card_bottom_text_ul">
                                        @foreach ($b->cat as $c)
                                        <li>
                                            <a href="#">{{$c->categoryName}}</a>
                                            <span><i class="fa fa-angle-right"></i></span>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                    <a href="/blog/{{$b->slug}}">
                                        <h2 class="home_card_h2">{{$b->title}}</h2>
                                    </a>
                                    <p class="post_p">
                                        {{$b->post_excerpt}}
                                    </p>
                                    <div class="home_card_bottom_tym">
                                        <div class="home_card_btm_left">
                                            <img src="#" alt="image">
                                        </div>
                                        <div class="home_card_btm_r8">
                                            
                                                <p class="author_name">{{$b->user->fullName}}</p>

                                            <ul class="home_card_btm_r8_ul">
                                                <li>Dec 4, 2019</li>
                                                <li><span class="dot"></span>3 Min Read</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
                @endif
            </div>

                {!! $blogs->links() !!}

        </div>
    </div>

</div>
<!-- BODY -->
@endsection
