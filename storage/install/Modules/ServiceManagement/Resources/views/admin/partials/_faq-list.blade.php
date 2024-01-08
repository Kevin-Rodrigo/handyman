<div class="accordion mb-30" id="accordionExample">
    @if($faqs->count() < 1)
        <img src="{{asset('public/assets/admin-module')}}/img/icons/faq.png" class="mb-4"
             alt="">
        <h3 class="text-muted">{{translate('no_faq_added_yet')}}</h3>
    @endif
    @foreach($faqs as $faq)
        <form action="{{route('admin.faq.update',[$faq->id])}}" method="POST" class="mb-30 hide-div"
              id="edit-{{$faq->id}}">
            @csrf
            @method('PUT')
            <div class="form-floating mb-30">
                <input type="text" class="form-control" placeholder="{{translate('question')}}" name="question"
                       value="{{$faq->question}}"
                       required="">
                <label>{{translate('question')}}</label>
            </div>
            <div class="form-floating mb-30">
                <textarea class="form-control" placeholder="{{translate('answer')}}"
                          name="answer">{{$faq->answer}}</textarea>
                <label>{{translate('answer')}}</label>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" onclick="ajax_post('edit-{{$faq->id}}')"
                        class="btn btn--primary">{{translate('update_faq')}}</button>
            </div>
        </form>

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
                        <label class="switcher" data-bs-toggle="modal" data-bs-target="#deactivateAlertModal">
                            <input class="switcher_input" type="checkbox" {{$faq->is_active?'checked':''}} onclick="ajax_status_update('{{route('admin.faq.status-update',[$faq->id])}}','faq-list')">
                            <span class="switcher_control"></span>
                        </label>
                    </div>

                    <button type="button" onclick="$('#edit-{{$faq->id}}').toggle()"
                            class="accordion-edit-btn bg-transparent border-0 p-0">
                        <span class="material-icons">border_color</span>
                    </button>

                    <button type="button"
                            onclick="ajax_delete('{{route('admin.faq.delete',[$faq->id,$faq->service_id])}}')"
                            class="accordion-delete-btn bg-transparent border-0 p-0"
                            data-bs-toggle="modal" data-bs-target="#deleteAlertModal">
                        <span class="material-icons">delete</span>
                    </button>
                </div>
            </div>
            <div id="faq_{{$faq->id}}" class="accordion-collapse collapse"
                 aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    {{$faq->answer}}
                </div>
            </div>
        </div>
    @endforeach
</div>
