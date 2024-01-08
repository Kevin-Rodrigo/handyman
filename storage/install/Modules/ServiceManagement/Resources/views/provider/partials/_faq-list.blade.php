<div class="accordion mb-30" id="accordionExample">
    @if(count($faqs) > 0)
        @foreach($faqs as $faq)
            @if($faq->is_active == '1')
            <div class="accordion-item">
                <div class="accordion-header d-flex flex-wrap flex-sm-nowrap gap-3"
                     id="headingOne">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq_{{$faq->id}}"
                            aria-expanded="false" aria-controls="{{$faq->id}}">
                        {{$faq->question}}
                    </button>
                    <div class="btn-group d-flex gap-3 align-items-center">
                        <div>
                            {{$faq->is_active?translate('active'):translate('inactive')}}
                        </div>
                    </div>
                </div>
                <div id="faq_{{$faq->id}}" class="accordion-collapse collapse"
                     aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        {{$faq->answer}}
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    @else
        <span>{{translate('No_FAQ_available_yet')}}</span>
    @endif
</div>
