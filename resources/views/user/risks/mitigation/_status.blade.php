@if ($mitigation->progress == 25)
    <div class="progress mb-1" style="height: 5px;">
        <div class="progress-bar bg-success" role="progressbar"
             style="width: 25%;" aria-valuenow="25" aria-valuemin="0"
             aria-valuemax="100"></div>
    </div>
    <p class="fs-xs fw-semibold mb-0">25%</p>
    <p style="display: none">In progress - 25%|50%|75%</p>
@endif
@if ($mitigation->progress == 50)
    <div class="progress mb-1" style="height: 5px;">
        <div class="progress-bar bg-success" role="progressbar"
             style="width: 50%;" aria-valuenow="50" aria-valuemin="0"
             aria-valuemax="100"></div>
    </div>
    <p class="fs-xs fw-semibold mb-0">50%</p>
    <p style="display: none">In progress - 25%|50%|75%</p>
@endif
@if ($mitigation->progress == 75)
    <div class="progress mb-1" style="height: 5px;">
        <div class="progress-bar bg-success" role="progressbar"
             style="width: 75%;" aria-valuenow="100" aria-valuemin="0"
             aria-valuemax="100"></div>
    </div>
    <p class="fs-xs fw-semibold mb-0">75%</p>
    <p style="display: none">In progress - 25%|50%|75%</p>
@endif
@if ($mitigation->progress == 100)
    <div class="progress mb-1" style="height: 5px;">
        <div class="progress-bar bg-success" role="progressbar"
             style="width: 100%;" aria-valuenow="10" aria-valuemin="0"
             aria-valuemax="100"></div>
    </div>
    <p class="fs-xs fw-semibold mb-0">100%</p>
    <p style="display: none">Completed - 100%</p>
@endif
@if ($mitigation->progress == 0)
    <div class="progress mb-1" style="height: 5px;">
        <div class="progress-bar bg-success" role="progressbar"
             style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
             aria-valuemax="100"></div>
    </div>
    <p class="fs-xs fw-semibold mb-0">0%</p>
    <p style="display: none">Not started - 0%</p>
@endif
