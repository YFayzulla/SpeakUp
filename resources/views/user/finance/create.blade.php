<div class="d-flex justify-content-between">
    <div class="col-lg-4 col-md-6">
        <div class="mt-3">
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#basicModal">
                <i class="bx bx-plus"></i>
            </button>

            <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Create finance for the teacher</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('finance.store_other') }}">
                                @csrf
                                <div class="row g-2">
                                    <div class="col mb-3">
                                        <label for="reason" class="form-label">Reason</label>
                                        <input type="text" id="reason" class="form-control" name="reason" value="{{ old('reason') }}" required/>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col mb-3">
                                        <label for="payment" class="form-label">Payment</label>
                                        <input type="number" id="payment" class="form-control" name="payment" value="{{ old('payment') }}" required/>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col mb-3">
                                        <label for="type" class="form-label">Type</label>
                                        <select id="type" class="form-control" name="type" required>
                                            <option value="{{ \App\Models\Finance::CASH }}">Cash</option>
                                            <option value="{{ \App\Models\Finance::CARD }}">Card</option>
                                        </select>
                                    </div>
                                </div>
{{--                                <input type="hidden" name="status" value="{{ \App\Models\Finance::STATUS_INCOME }}">--}}
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
