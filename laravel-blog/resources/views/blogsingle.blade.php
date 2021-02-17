@extends('layout.app')
@section('title',$blog->title)
@section('maincontent')

<!-- BANNER -->
<div class="blog_banner">

</div>
<!-- BANNER -->
		<!-- BODY -->
		<div class="blog_post_sec_all">
			<div class="container">
				<div class="row">
					<div class="cl-12 col-md-12 col-lg-9">
						<div class="blog_post_left">
							<div class="blog_post_sec">
								<div class="blog_post_top">
									<ul class="blog_post_top_ul">
                                        @if (count($blog->cat)>0)
                                        @foreach ($blog->cat as $c)
                                        <li>
											<a href="blog.html">{{$c->categoryName}}</a>
										</li>
                                        @endforeach

                                        @endif

										<li>1 Feb, 2020</li>
									</ul>
								</div>
								<div class="blog_post">

                                    <h1 class="blog_post_h1">{{$blog->title}}</h1>
									<div class="post_author_sec">
										<div class="post_author_left">
											<div class="post_author_img">
												<img src="" alt="image">
											</div>
											<div class="post_author_info">
											<a href=""><h4 class="post_author_title">{{$blog->user->fullName}}</h4></a>

											</div>
										</div>
										<div class="post_author_r8">
											<i class="fa fa-share-alt"></i>
										</div>
									</div>
                                    {!! $blog->post !!}

									<div class="riview_post">
										<ul class="riview_post_ul">
											<li><i class="fa fa-thumbs-up"></i>122 Like</li>
											<li><i class="fa fa-comments"></i>10 Comment</li>
											<li><i class="fa fa-share-square"></i>4 Share</li>
										</ul>
									</div>
								</div>
							</div>

						<comment></comment>

							<!--WRITE COMMENT BOX -->
								<writecomment></writecomment>
							<!--WRITE COMMENT BOX -->
						</div>
					</div>
					<div class="col-12 col-md-12 col-lg-3">
						<div class="blog_post_r8">
								<h4 class="trnd_artcl_h4">TRENDING ARTICLES</h4>
							<div class="blog_post_r8_all">
                                <!-- iteam -->
                                @if (count($relatedBlogs)>0)
                                @foreach ($relatedBlogs as $b)
                                <div class="blog_post_r8_item">
									<div class="blog_post_item_lft">
										<img src="" alt="image">
									</div>
									<div class="blog_post_item_r8">
										<a href="/blog/{{$b->slug}}">
											<h4 class="blog_post_item_r8_h4">
												{{$b->title}}
											</h4>
										</a>
										<a href=""><p class="author_name2">{{$b->user->fullName}}</p></a>
									</div>
								</div>
								<!-- iteam -->
                                @endforeach

                                @endif


							</div>
							<div class="course_price mar_t60">
								<div class="course_price_top">
									<p>Share this post</p>
								</div>
								<div class="course_price_main" style="padding: 30px 0px 30px 17px">
									<ul class="share_social_ul dis_flx">
										<li>
											<a class="fb" href="">
												<i class="fa fa-facebook-f"></i>
											</a>
										</li>
										<li>
											<a class="google" href="">
												<i class="fa fa-google-plus-g"></i>
											</a>
										</li>
										<li>
											<a class="instgrm" href="">
												<i class="fa fa-instagram"></i>
											</a>
										</li>
										<li>
											<a class="twtr" href="">
												<i class="fa fa-twitter"></i>
											</a>
										</li>
										<li>
											<a class="skpye" href="">
												<i class="fa fa-skype"></i>
											</a>
										</li>
										<li>
											<a class="utube" href="">
												<i class="fa fa-youtube"></i>
											</a>
										</li>
								<!-- 		<li>
											<a class="lnkdn" href="">
												<i class="fa fa-linkedin-in"></i>
											</a>
										</li>
										<li>
											<a class="pinstr" href="">
												<i class="fa fa-pinterest"></i>
											</a>
										</li> -->
									</ul>
								</div>
							</div>
							<div class="post_tags">
								<h3 class="post_tags_h3">Tags</h3>
								<ul class="post_tags_ul">
                                    @if (count($blog->tag)>0)
                                    @foreach ($blog->tag as $t)
                                    <li>
                                        <a href="/tag/{{$t->tagName}}/{{$t->id}}">{{$t->tagName}}</a>
									</li>
                                    @endforeach
                                    @endif

								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- BODY -->


@endsection
