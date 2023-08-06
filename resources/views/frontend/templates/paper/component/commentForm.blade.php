<div class="panel">
    <div class="panel-body">
        <div class="alert hide response-message" id="response-message" role="alert"></div>
        <form class="form-contact contact_form mb-80" action="{{ route('paper_add_comment', ['paper_id' => $paper->id]) }}"
            method="post" id="contactForm">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <textarea class="form-control w-100 error" name="message" id="message" cols="30" rows="9"
                            onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Message'" placeholder="Enter Message"></textarea>
                    </div>
                </div>

                <input type="hidden" name="paper_id" value="{{ $paper->id }}">

                @if (!Auth::check())
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input class="form-control error" name="name" id="name" type="text"
                                onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'"
                                placeholder="Enter your name">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input class="form-control error" name="email" id="email" type="email"
                                onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email address'"
                                placeholder="Email">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <input class="form-control error" name="subject" id="subject" type="text"
                                onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Subject'"
                                placeholder="Enter Subject">
                        </div>
                    </div>
                @endif

            </div>
            <div class="form-group mt-3">
                <button type="submit" class="button button-contactForm boxed-btn boxed-btn2">Send</button>
            </div>
        </form>
        <div class="mar-top clearfix">
            <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-pencil fa-fw"></i>
                Share</button>
            <a class="btn btn-trans btn-icon fab fa-video-camera add-tooltip" href="#"><i
                    class="fa fa-camera"></i></a>
            <a class="btn btn-trans btn-icon fab fa-camera add-tooltip" href="#"><i class="fa fa-video"></i>
            </a>
            <a class="btn btn-trans btn-icon fab fa-camera add-tooltip" href="#"><i class="fa fa-file"></i>
            </a>
        </div>
    </div>
</div>

<script>
    setInterval(() => {
        var error_message = $(".response-message");
        if (error_message.length) {
            $(error_message).hide();
        }
    }, 4000);
</script>
