<!-- Service Update Modal -->
<div class="modal fade" id="serviceUpdateModal--{{$booking['id']}}" tabindex="-1"
     aria-labelledby="serviceUpdateModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header px-4 pt-4 border-0 pb-1">
                <h3 class="text-capitalize">{{translate('update_booking_list')}}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <!-- Add Service -->
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div class="mb-30" data-bs-toggle="tooltip" data-bs-placement="top"
                             title="{{translate('Can not change Category')}}">
                            <select class="theme-input-style w-100 disabled" id="category_selector__select"
                                    name="category_id" readonly disabled>
                                <option value="{{$category?->id}}" selected>{{$category?->name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="mb-30" data-bs-toggle="tooltip" data-bs-placement="top"
                             title="{{translate('Can not change Sub Category')}}">
                            <select class="theme-input-style w-100 disabled" id="sub_category_selector__select"
                                    name="sub_category_id" readonly disabled>
                                <option value="{{$sub_category?->id}}" selected>{{$sub_category?->name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="mb-30">
                            <select class="theme-input-style w-100" id="service_selector__select" name="service_id"
                                    required>
                                <option value="" selected disabled>{{translate('Select Service')}}</option>
                                @foreach($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="mb-30">
                            <select class="theme-input-style w-100" id="service_variation_selector__select"
                                    name="variant_key" required>
                                <option selected disabled>{{translate('Select Service Variant')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="mb-30">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="service_quantity" id="service_quantity"
                                       placeholder="{{translate('service_quantity')}}" min="1"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                <label>{{translate('service_quantity')}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <input type="hidden" name="booking_id" value="{{$booking->id}}">
                        <div class="d-flex gap-3 justify-content-end mb-4">
                            <button type="reset" class="btn btn--secondary">{{translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary" id="add-service">{{translate('Add Service')}}</button>
                        </div>
                    </div>
                </div>

                <!-- Service table -->
                <form action="{{route('admin.booking.service.update_booking_service')}}" method="POST" id="booking-edit-table">
                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0" id="service-edit-table">
                            @csrf
                            @method('put')
                            <thead>
                            <tr>
                                <th class="ps-lg-3">{{translate('Service')}}</th>
                                <th>{{translate('Price')}}</th>
                                <th>{{translate('Qty')}}</th>
                                <th>{{translate('Discount')}}</th>
                                <th>{{translate('Total')}}</th>
                                <th class="text-center">{{translate('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="service-edit-tbody">
                            @php($sub_total=0)
                            @foreach($booking->detail as $key=>$detail)
                                <tr id="service-row--{{$detail?->variant_key}}">
                                    <td class="text-wrap ps-lg-3">
                                        @if(isset($detail->service))
                                            <div class="d-flex flex-column">
                                                <a href="{{route('admin.service.detail',[$detail->service->id])}}"
                                                   class="fw-bold">{{Str::limit($detail->service->name, 30)}}</a>
                                                <div>{{Str::limit($detail ? $detail->variant_key : '', 50)}}</div>
                                            </div>
                                        @else
                                            <span class="badge badge-pill badge-danger">{{translate('Service_unavailable')}}</span>
                                        @endif
                                    </td>
                                    <td id="service-cost-{{$detail?->variant_key}}">{{with_currency_symbol($detail->service_cost)}}</td>
                                    <td>
                                        <input type="number" min="1" name="qty[]" class="form-control qty-width"
                                               id="qty-{{$detail?->variant_key}}" value="{{$detail->quantity}}"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                               readonly>
                                    </td>
                                    <td id="discount-amount-{{$detail?->variant_key}}">{{with_currency_symbol($detail->discount_amount)}}</td>
                                    <td id="total-cost-{{$detail?->variant_key}}">{{with_currency_symbol($detail->total_cost)}}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <span class="material-icons text-danger cursor-pointer"
                                                  onclick="removeServiceRow('service-row--{{$detail?->variant_key}}')">delete</span>
                                        </div>
                                    </td>
                                    <input type="hidden" name="service_ids[]" value="{{$detail->service->id}}">
                                    <input type="hidden" name="variant_keys[]" value="{{$detail->variant_key}}">
                                </tr>
                                @php($sub_total += $detail->service_cost*$detail->quantity)
                            @endforeach

                            <input type="hidden" name="zone_id" value="{{$booking->zone_id}}">
                            <input type="hidden" name="booking_id" value="{{$booking->id}}">
                            </tbody>
                        </table>
                    </div>
                </form>
                <!-- End table -->

            </div>
            <div class="modal-footer d-flex justify-content-end gap-3 border-0 pt-0 pb-4">
                <button type="button" class="btn btn--secondary" data-bs-dismiss="modal" aria-label="Close">{{translate('Cancel')}}</button>
                <button type="submit" class="btn btn--primary" form="booking-edit-table">{{translate('update_cart')}}</button>
            </div>
        </div>
    </div>
</div>
