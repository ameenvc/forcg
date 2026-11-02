<!-- Close Mitigating Modal -->
<div class="modal close-mitigating-popup"
     id="modal-close-mitigating-{{ $mitigation->id }}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog m-dialog modal-xl" role="document">
        <div class="modal-content" style="text-align: left">
            <form action="{{ route('mitigations.close', $mitigation->id) }}" method="POST">
                @csrf
                @method('put')
                <div class="modal-body m-body p-4">
                    <div class="form-group" style="display:inline-block;width:99%;">
                        <label class="text-center" style="color: red">
                            Your mitigation action may have reduced a certain percentage of the likelihood, impact, or both,
                            consequently, affecting the overall risk. Please update below, how much this mitigation action
                            has contributed to risk reduction
                        </label>
                    </div>
                    @include('user.risks.mitigation._close_mitigation_form_item')
                    @if(isset($otherRisks))
                        @foreach($otherRisks as $otherRisk)
                            @include('user.risks.mitigation._close_mitigation_form_item', [
                                'risk' => $otherRisk,
                                'isOtherLinkedRisk' => true
                            ])
                        @endforeach
                    @endif
                    <!-- Save button -->
                    <div class="mt-5 d-flex justify-content-end">
                        <button type="button" class="btn btn-lg btn-outline-primary mx-3 px-5" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-lg btn-primary px-5 btn-close-mitigation">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

