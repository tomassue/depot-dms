<div>
    @include('livewire.template.loading-spinner')

    <div class="card mb-2">
        <div class="card-body">
            <h4 class="card-title">Mechanics Job Order Report</h4>
            <div class="row g-2">
                <div class="col-lg-2 d-flex flex-column align-items-start" title="Filter">
                    <div class="form-group w-100">
                        <select class="form-select" aria-label="Default select example" style="height: 48px; border-radius: unset;" wire:model.live="filter_section_mechanics_job_orders">
                            <option value="" selected>Section</option>
                            @foreach ($filter_sections as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 d-flex flex-column align-items-start" title="Filter">
                    <div class="form-group w-100">
                        <select class="form-select" aria-label="Default select example" style="height: 48px; border-radius: unset;" wire:model.live="filter_sub_section_mechanics_job_orders">
                            <option value="" selected>Sub-section</option>
                            @foreach ($filter_sub_sections as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12 d-flex flex-column align-items-start" title="Filter">
                    <div class="col-lg-2">
                        <div class="form-group w-100">
                            <div wire:ignore>
                                <input class="form-control filter_date_range_mechanics_job_orders" placeholder="Date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 d-flex flex-column align-items-start" title="Filter">
                    <div class="col-lg-2">
                        <div class="form-group w-100">
                            <input type="text" class="form-control" placeholder="Search" wire:model.live="search">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 my-2 d-inline-flex align-items-center mb-5" title="Filter">
                <!-- <button class="btn btn-primary btn-md btn-icon-text me-2" wire:click="filter">
                    <i class="bx bx-filter-alt bx-sm"></i>
                </button> -->

                @can('print reports')
                <button class="btn btn-info btn-md btn-icon-text me-4" title="Print" wire:click="printMechanicsJobOrders">
                    <i class="bx bx-printer bx-sm"></i>
                </button>
                @endcan

                <button class="btn btn-secondary btn-md btn-icon-text" title="Clear" wire:click="clear">
                    <i class="bx bxs-eraser bx-sm"></i>
                </button>
            </div>

            <div class="col-md-12 my-2">
                <div id="table_mechanic_job_orders" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- pdfModal -->
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="pdfModalModalLabel">PDF</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <embed src="{{ $pdfData }}" type="application/pdf" width="100%" height="700px">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('show-pdf', () => {
        $('#pdfModal').modal('show');
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($mechanics);
    const table_requests = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            'Name',
            'Section',
            'Sub-section',
            'Pending',
            'Completed',
            'Total'
        ],
        data: () => {
            return new Promise(resolve => {
                setTimeout(() =>
                    resolve(
                        data.map(item => [
                            item.id,
                            item.name,
                            item.section.name,
                            item.sub_section?.name ?? '-',
                            item.pending_jobs,
                            item.completed_jobs,
                            item.total_jobs
                        ])
                    ), 1000);
            });
        },
        pagination: {
            limit: 10
        },
        sort: true,
        autoWidth: true
    }).render(document.getElementById("table_mechanic_job_orders"));

    $wire.on('refresh-table-mechanic-job-orders', (data) => {
        table_requests.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id,
                                item.name,
                                item.section.name,
                                item.sub_section?.name ?? '-',
                                item.pending_jobs,
                                item.completed_jobs,
                                item.total_jobs
                            ])
                        ), 1000);
                });
            }
        }).forceRender();
    });

    /* -------------------------------------------------------------------------- */

    $(".filter_date_range_mechanics_job_orders").flatpickr({
        mode: "range",
        altInput: true,
        altFormat: 'M j, Y',
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr) {
            @this.set('filter_date_range_mechanics_job_orders', dateStr);
        }
    });

    $wire.on('set-date_range_mechanics_job_orders', (key) => {
        $(".filter_date_range_mechanics_job_orders")[0]._flatpickr.setDate(key[0]);
        @this.set('filter_date_range_mechanics_job_orders', key[0]);
    });

    $wire.on('reset-filter-date-range-mechanics-job-orders', () => {
        $(".filter_date_range_mechanics_job_orders")[0]._flatpickr.clear(); // Clear the Flatpickr input without using a variable
        @this.set('filter_date_range_mechanics_job_orders', null);
    });
</script>
@endscript