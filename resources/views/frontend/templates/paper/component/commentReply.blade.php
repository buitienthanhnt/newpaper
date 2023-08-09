<a class="btn btn-primary" data-toggle="collapse" href="#{{ $item->id }}-reply" aria-expanded="false">
    Reply
</a>

<div class="collapse" id="{{ $item->id }}-reply" data-parent="#commentHistory">
	<div class="alert alert-success hide response-message" id="response-message-{{ $item->id }}" role="alert"></div>
    <div class="row">
        <div class="col-12">
            <textarea class="form-control w-100 error" name="reply-comment-{{ $item->id }}" id="reply-comment-{{ $item->id }}"
                cols="10" rows="4" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Message'"
                placeholder="Enter Message"></textarea>
        </div>

        @if (!Auth::check())
            <div class="col-6">
                <input class="form-control error" name="reply-name-{{ $item->id }}" id="eply-name-{{ $item->id }}" type="text"
                    onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'"
                    placeholder="Enter your name">
            </div>
            <div class="col-6">
                <input class="form-control error" name="reply-email-{{ $item->id }}" id="reply-email-{{ $item->id }}" type="email"
                    onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email address'"
                    placeholder="Email">
            </div>
        @endif

    </div>


    <div class="float-right">
        <span class="btn btn-info" onclick="sendReply(this)" data-comment-id="{{ $item->id }}">send <i
                class="fa fa-reply"></i></span>
    </div>
</div>
