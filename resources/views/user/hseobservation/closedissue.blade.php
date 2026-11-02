@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div>
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue2')}}" style="margin-bottom: 0px">
                @csrf

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue2" name="tvalue2" />
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
    </ul>
</div>
@endsection
@section('content')
<style>
    body {
        overflow-y: auto;
    }
    .headingselect {
        white-space: break-spaces;
        line-height: 1rem;
    }
    .border-3 {
        border-width: 3px !important;
    }

    .font-15{
        font-size:12px !important;
    }
    .breadcrumb>li+li:before {
        display: none !important;
    }
    .select2-results__options{
        font-size:12px !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    } 
    .titleheading {
        font-size: 10px !important;
        text-transform: capitalize !important;
        position: sticky !important;
        position: relative !important;
        font-weight:bold !important;
        min-width: 120px !important;
    }
    .scrollable {
        height: 100%;
        overflow-x: scroll;
        overflow-y: scroll;
    }
    @media (max-width: 1800px) {
        #content {
            height: calc(100vh - 220px) !important;
            max-height: 900px !important;
        }
    }
    @media (max-width: 1200px) {
        #content {
            max-height: 600px !important;
        }
    }
    #content{
        height:calc(100vh - 220px); max-height:900px;
    }
</style>
<?php
    $issues2=$issues=$issues3=[];
?>
<input type="hidden" value="a2" id="tabselected">
<div class="content" style="width:100%">
    <!-- Dynamic Table Full -->
    <div class="block block-rounded">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">               
                <li class="breadcrumb-item font-w700 text-uppercase" aria-current="page">
                    HSE Observations
                </li>
                <li class="breadcrumb-item">
                    Closed Issues
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <!--shift header to white background-->
            <div class="scrollable table-responsive" id="content">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full text-center" id="tabledata">
                    <thead>
                        <tr style="position: sticky; top: -2px; z-index: 5;">
                            <td class="text-center bg-white titleheading" style="font-size:10px;font-weight:bold !important;min-width:20px !important;" data-orderable="false">Slno</td>
                            <td class="text-center titleheading bg-white" style="font-size:10px;font-weight:bold !important;min-width:20px !important;" data-orderable="false">Id</td>
                            <td class="text-center titleheading bg-white" data-orderable="false">Title</td>
                            <td id="categories101" class="text-center titleheading bg-white" data-orderable="false"></td>
                            <td id="departments101" class="text-center titleheading bg-white" data-orderable="false"></td>
                            <td id="types101" class="text-center titleheading bg-white" style="min-width:60px !important;" data-orderable="false"></td>
                            <td id="duedates101" class="titleheading text-center bg-white" data-orderable="false"></td>
                            <td id="processstageshead101" data-orderable="false" class="text-center titleheading bg-white"></td>
                            <td class="text-center bg-white titleheading" style="font-size:10px;font-weight:bold !important;min-width:fit-content !important;" data-orderable="false">Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=0;?>
                        @foreach ($issues1 as $issue)
                        <?php $rr=$issue->id;
                            $date = $issue->duedate;
                            $today = Date("Y-m-d");
                        ?>
                        <tr>
                            <td class="text-center" style="font-size:10px;">{{ $no=$no+1}}</td>
                            @if($issue->category=="Internal Audit")
                            <td class="text-center " style="font-size:10px;width: 5%;color:#0033cc">{{$issue->issueid}}</td>
                            @elseif($issue->category=="External Audit")
                            <td class="text-center " style="font-size:10px;width: 5%;color:orange">{{$issue->issueid}}</td>
                            @elseif($issue->category=="Management Review")
                            <td class="text-center " style="font-size:10px;width: 5%;color:#33cc33">{{$issue->issueid}}</td>
                            @elseif($issue->category=="Corrective Action")
                            <td class="text-center " style="font-size:10px;width: 5%;color:#ff0066">{{$issue->issueid}}</td>
                            @else
                            <td class="text-center " style="font-size:10px;width: 5%;color:black">{{$issue->issueid}}</td>
                            @endif
                            <td class=" font-w600 " style="font-size:10px;">
                                <a href="#">{{$issue->title}}</a>
                            </td>
                            <td style="font-size:10px;color:black">
                                {{$issue->categoryName}}
                                <div style="display: none;">{{'e_'.$issue->categoryName.'_f'}}</div>
                            </td>
                            <td style="font-size:10px;">
                                {{$issue->departmentHse ? $issue->departmentHse->departmentname : $issue->department}}
                                <div style="display: none;">{{'e_'.$issue->categoryName.'_f'}}{{$issue->departmentHse ? 'e_'.$issue->departmentHse->departmentname.'_f' : 'e_'.$issue->department.'_f'}}</div>
                            </td>
                            <td class="text-center" style="font-size:12px;">
                                @if(isset($hsetypes[$issue->type]) && $hsetypes[$issue->type]=="Low")
                                <span class="badge badge-success">{{$hsetypes[$issue->type]}}</span>
                                @elseif(isset($hsetypes[$issue->type]) && $hsetypes[$issue->type]=="Moderate")
                                <span class="badge badge-warning">{{$hsetypes[$issue->type]}}</span>
                                @elseif(isset($hsetypes[$issue->type]) && $hsetypes[$issue->type]=="High")
                                <span class="badge badge-danger">{{$hsetypes[$issue->type]}}</span>
                                @else
                                <span class="badge badge-primary">{{$hsetypes[$issue->type]??'--'}}</span>
                                @endif
                                <div style="display: none;">{{isset($hsetypes[$issue->type]) ? 'e_'.$hsetypes[$issue->type].'_f' :''}}</div>
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                <em class="text-muted">{{date('d-m-Y',strtotime($issue->duedate))}}</em>
                                <div style="display: none;">{{'e_'.date('Y',strtotime($issue->duedate)).'_f'}}</div>
                            </td>
                            <td class="d-none d-sm-table-cell" style="font-size:10px;">
                                @if($issue->status == 'closed' && $issue->closed <= $date) 
                                <span class="badge badge-success">{{$issue->status}}</span>
                                @elseif($issue->status == 'closed' && $issue->closed > $date)
                                <?php $issue->status='closed after due' ?>
                                <span class="badge badge-warning">{{$issue->status}}</span>
                                @else
                                <span class="badge badge-primary">{{$issue->status}}</span>
                                @endif
                                <div style="display: none;">{{'e_'.$issue->status.'_f'}}</div>
                            </td>
                            <td class="font-w600 font-size-sm">
                                <div class="btn-group m-auto d-flex justify-content-center">
                                    <a href="#" class="btn btn-sm btn-alt-primary js-view-detail" data-id="{{ $issue->id }}" data-route="{{ route('hseobservation.show.closed.issue', $issue->id) }}" data-id="{{  $issue->id  }}">
                                        <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END Dynamic Table Full -->
    <div class="hse-closed-issue-area"></div>
</div>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/datatable_extension_for_complaint.js') }}"></script>
<script>
    $(document).ready(function() {
        $('body').on('click', '.js-view-detail', function (e) {
            e.preventDefault();
            const url = $(this).attr('data-route');
            const id = $(this).attr('data-id');
            $.ajax({
                url,
                type: 'GET',
                success: function (data) {
                    if (data.success) {
                        $('.hse-closed-issue-area').html(data.view);
                        const modal = $(`#viewModal${id}`);

                        if (modal.length) {
                            modal.modal('show');
                        }

                        $.ajax({
                            url: "/show/" + id + "/" + "hseissues"
                            , type: 'get'
                            , data: {
                                id: id
                            }
                            , dataType: "json"
                            , success: function(response) {
                                response.data.responsibleUser ? $('#responsible' + id).attr('src', '/storage/profile/' + response.data.responsibleUser.profile_picture) : $('#responsible').attr('src', '');
                                response.data.postedByUser ? $('#postedbyUser' + id).attr('src', '/storage/profile/' + response.data.postedByUser.profile_picture) : $('#postedbyUser').attr('src', '');
                            }
                        });
                    } else {
                        alert('Data is not found')
                    }
                }
            })
        })
    });
    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 100);

</script>
<script>
    $(document).ready(function () {
        function initializeDropdowns(tableId, dropdownConfigs) {
            $(tableId).one("preInit.dt", function () {
                let table = $(tableId).DataTable();
                let previousSelectedValue = null;
                dropdownConfigs.forEach(config => {
                    let { dropdownId, columnHeaderId, optionsArray, placeholder,isDateFilter} = config;
                    let columnIndex = table.column(columnHeaderId).index();
                    let optionsHTML = optionsArray.map(opt => `<option value="${opt}">${opt}</option>`).join('');
                    let dropdownHTML = '';
                    if (isDateFilter) {
                        const tree = createTree(optionsArray);
                        dropdownHTML = buildTreeHtml(tree, placeholder.replace(/ /g, '-'), placeholder);
                    } else {
                        const minWidth = placeholder === 'Complaint Categories' ? '234px' : 'fit-content';
                        dropdownHTML = `
                            <select class="headingselect text-center" style="border:none;-webkit-appearance: none;min-width:${minWidth};background-color:#fff;font-weight:600 !important;" id="${dropdownId}">
                                <option value="">${placeholder}&nbsp;&nbsp;&#9207;</option>
                                ${optionsHTML}
                            </select>
                        `;
                    }
                    $(columnHeaderId).html(dropdownHTML);
                    if (isDateFilter) {
                        const dateFilterElement = $('.' + placeholder.replace(/ /g, '-'));
                        dateFilterElement.find('.filter-header').click(function() {
                            $(this).next('.filter-content').slideToggle();
                        });
                        dateFilterElement.find('.expand').click(function(e) {
                            e.stopPropagation();
                            const dateFilter = $(this).closest('.date-filter')
                            if (dateFilter.next('.nested').length) {
                                dateFilter.next('.nested').slideToggle();
                                $(this).text($(this).text() === '-' ? '+' : '-');
                            }
                        });
                        dateFilterElement.find(".cke-filter").change(function() {
                            const eleName = $(this).attr('data-name')
                            const value = $(this).val();
                            let searchDataList = []
                            if(this.checked) {
                                if (eleName === "year") {
                                    dateFilterElement.find(".cke-filter[data-year=" + value + "]")
                                        .prop('checked', true);
                                }
                                else if (eleName === "month") {
                                    dateFilterElement.find(".cke-filter[data-month=" + value + "]")
                                        .prop('checked', true);
                                }
                            } else {
                                if (eleName === "year") {
                                    dateFilterElement.find(".cke-filter[data-year=" + value + "]")
                                        .prop('checked', false);
                                } else if (eleName === "month") {
                                    dateFilterElement.find(".cke-filter[data-month=" + value + "]")
                                        .prop('checked', false);
                                }
                            }

                            const yearHasBeenSelected = $(this).attr('data-year');
                            const monthHasBeenSelected = $(this).attr('data-month');

                            let isSelectAllMonth = true;
                            dateFilterElement.find(".cke-filter[data-month=" + monthHasBeenSelected + "]").each(function (index, item) {
                                if(!$(item).is(":checked")) {
                                    isSelectAllMonth = false;
                                    return false;
                                }
                            })
                            if (isSelectAllMonth) {
                                dateFilterElement.find(".cke-filter[data-name=month][value="+ monthHasBeenSelected + "]")
                                    .prop('checked', true);
                            } else {
                                dateFilterElement.find(".cke-filter[data-name=month][value="+ monthHasBeenSelected + "]")
                                    .prop('checked', false);
                            }
                            let isSelectAllYear = false;
                            dateFilterElement.find(".cke-filter[data-year=" + yearHasBeenSelected + "]").each(function (index, item) {
                                if($(item).is(":checked")) {
                                    isSelectAllYear = true;
                                    return false;
                                }
                            })
                            if (isSelectAllYear) {
                                dateFilterElement.find(".cke-filter[data-name=year][value="+ yearHasBeenSelected + "]")
                                    .prop('checked', true);
                            } else {
                                dateFilterElement.find(".cke-filter[data-name=year][value="+ yearHasBeenSelected + "]")
                                    .prop('checked', false);
                            }

                            dateFilterElement.find(".cke-filter[data-name=month]").each(function (index, item) {
                                if($(item).is(":checked")) {
                                    searchDataList.push($(item).val())
                                }
                            })
                            const searchKeyWord = searchDataList.join('|');
                            console.log(searchKeyWord);
                            table.column(columnIndex).search(searchKeyWord,true,false).draw()
                            $(this).closest('.filter-content').slideToggle();
                        });
                    }else{
                        $(`#${dropdownId}`).on('change', function () {
                            let selectedValue = $(this).val();
                            let $select = $(this);

                            let firstOption = $select.find("option:first");

                            if (selectedValue) {
                                previousSelectedValue = selectedValue;
                                table.column(columnIndex).search('e_' + selectedValue + '_f').draw();
                                $(this).addClass('text-primary');
                                $("#" + dropdownId + " option").css('color', 'black')
                            } else {
                                table.column(columnIndex).search('').draw();
                                $(this).removeClass('text-primary').val('');
                                if (previousSelectedValue) {
                                    const $prevOption = $select.find(`option[value="${previousSelectedValue}"]`);
                                    if ($prevOption.length) {
                                        $prevOption.text(previousSelectedValue);
                                    }
                                }
                                firstOption.html(`${placeholder}&nbsp;&nbsp;&#9207;`);
                            }
                        });
                    }
                });
            });
        }

        // Prepare dropdown data from Blade
        let processStages = "{{ $allpstages }}".split(',');
        let types101 = "{{ $alltypenames }}".split(',');
        let departments101 = "{{ $alldeptnames }}".split(',');
        let dueDates101 = "{{ $years }}".split(',');
        let categories101 = "{{ $allcategoriesname }}".split(',');
        // Initialize dropdowns for table #tabledata_1
        initializeDropdowns('#tabledata', [
            {
                dropdownId: 'processstages101_dropdown',
                columnHeaderId: '#processstageshead101',
                optionsArray: processStages,
                placeholder: 'Status',
                isDateFilter:false
            },
            {
                dropdownId: 'types101_dropdown',
                columnHeaderId: '#types101',
                optionsArray: types101,
                placeholder: 'Type',
                isDateFilter:false
            },
            {
                dropdownId: 'departments101_dropdown',
                columnHeaderId: '#departments101',
                optionsArray: departments101,
                placeholder: 'Department',
                isDateFilter:false
            },
            {
                dropdownId: 'categories101_dropdown',
                columnHeaderId: '#categories101',
                optionsArray: categories101,
                placeholder: 'Category',
                isDateFilter:false
            },
            {
                dropdownId: 'duedates101_dropdown',
                columnHeaderId: '#duedates101',
                optionsArray: dueDates101,
                placeholder: 'Due Date',
                isDateFilter:false
            }
        ]);
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || window.performance.getEntriesByType("navigation")[0].type === "back_forward") {
                // Reset all dropdowns
                $(".headingselect").each(function () {
                    $(this).val(""); // reset dropdown
                    $(this).removeClass('text-primary'); // remove styling
                    const placeholder = $(this).find("option:first").text().split(' ')[0]; // e.g., 'Department'
                    $(this).find("option:first").text(`${placeholder}`);
                });
            }
        });
    });
</script>
<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Options</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <div class="row gutters-tiny">
                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/hseobservation/issues">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/closedissue">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-check-square fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Closed Issues
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/openissue">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        My Open Issues
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/analysis">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Analysis
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page JS Plugins -->
@include('exporting.foractionsystem')
@endsection
